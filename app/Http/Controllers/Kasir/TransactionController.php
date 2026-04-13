<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoursePackage;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Schedules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('details.package')->latest()->paginate(10);
        
        // Log Aktivitas: Melihat Daftar Transaksi
        logActivity('LIHAT TRANSAKSI', 'Membuka halaman daftar semua transaksi kasir.');

        return view('kasir.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $packages = CoursePackage::with(['items.course.mentor', 'items.course.schedules.room'])->get();
        
        // Log Aktivitas: Membuka Form Transaksi Baru
        logActivity('AKSES KASIR', 'Membuka halaman input transaksi baru (Form Kasir).');

        return view('kasir.transactions.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'packages'       => 'required|array|min:1',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $packages = CoursePackage::whereIn('id', $request->packages)->get();
            $total    = $packages->sum('price');
            $payment  = $request->payment_amount;

            if ($payment >= $total) {
                $status = 'paid'; 
                $paid_amount = $total; 
                $change = $payment - $total;
            } else {
                $status = 'dp'; 
                $paid_amount = $payment; 
                $change = 0;
            }

            $transaction = Transaction::create([
                'invoice_code'     => $this->generateInvoice(),
                'customer_name'    => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_phone'   => $request->customer_phone,
                'user_id'          => auth()->id(),
                'total_price'      => $total,
                'total_paid'       => $paid_amount,
                'payment_amount'   => $payment,
                'change_amount'    => $change,
                'payment_status'   => $status,
                'status'           => 'paid', 
            ]);

            foreach ($packages as $pkg) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'package_id'     => $pkg->id,
                    'price'          => $pkg->price,
                    'qty'            => 1,
                    'subtotal'       => $pkg->price,
                ]);

                // --- LOGIKA PENGURANGAN KAPASITAS ---
                foreach ($pkg->items as $item) {
                    $schedules = Schedules::where('course_id', $item->course_id)->get();

                    if ($schedules->isEmpty()) {
                        throw new Exception("Kursus " . ($item->course->name ?? 'N/A') . " belum memiliki jadwal.");
                    }

                    foreach ($schedules as $schedule) {
                        if ($schedule->capacity <= 0) {
                            throw new Exception("Kuota penuh untuk " . $item->course->name . " pada hari " . $schedule->day_of_week);
                        }
                        $schedule->decrement('capacity', 1);
                    }
                }
            }

            // Log Aktivitas: Transaksi Baru Berhasil
            logActivity(
                'TRANSAKSI BARU',
                'Membuat invoice ' . $transaction->invoice_code . ' untuk ' . $transaction->customer_name . ' (Status: ' . strtoupper($status) . ')'
            );

            DB::commit();
            return redirect()->route('kasir.transactions.show', $transaction->id)
                             ->with('success', 'Transaksi berhasil dan kuota jadwal telah diperbarui!');

        } catch (Exception $e) {
            DB::rollBack();

            // Log Aktivitas: Gagal Melakukan Transaksi
            logActivity(
                'GAGAL TRANSAKSI',
                'Gagal input transaksi untuk pelanggan ' . $request->customer_name . '. Error: ' . $e->getMessage()
            );

            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function payRemaining(Request $request, $id)
    {
        $request->validate(['payment_amount' => 'required|numeric|min:1']);
        
        $transaction = Transaction::findOrFail($id);
        $remaining = $transaction->total_price - $transaction->total_paid;

        if ($request->payment_amount < $remaining) {
            return back()->with('error', 'Uang pelunasan kurang dari sisa tagihan!');
        }

        try {
            $transaction->update([
                'total_paid'     => $transaction->total_price,
                'payment_status' => 'paid',
                'payment_amount' => $request->payment_amount,
                'change_amount'  => $request->payment_amount - $remaining,
            ]);

            // Log Aktivitas: Pelunasan
            logActivity(
                'PELUNASAN TRANSAKSI',
                'Pelunasan invoice ' . $transaction->invoice_code . ' oleh ' . $transaction->customer_name . ' sebesar Rp ' . number_format($remaining, 0, ',', '.')
            );

            return redirect()->route('kasir.transactions.show', $transaction->id)
                             ->with('success', 'Pelunasan berhasil diproses!');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses pelunasan.');
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['details.package']);

        // Log Aktivitas: Melihat Detail Invoice
        logActivity('LIHAT DETAIL INVOICE', 'Melihat detail transaksi invoice: ' . $transaction->invoice_code);

        return view('kasir.transactions.show', compact('transaction'));
    }

    public function pelanggan()
    {
        $customers = Transaction::select('customer_name', 'customer_phone', 'customer_address')
            ->groupBy('customer_name', 'customer_phone', 'customer_address')
            ->get();

        // Log Aktivitas: Melihat Data Pelanggan
        logActivity('LIHAT DATA PELANGGAN', 'Membuka database data pelanggan (pembeli).');

        return view('kasir.transactions.data_pelanggan', compact('customers'));
    }

    private function generateInvoice() {
        return 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));
    }
}