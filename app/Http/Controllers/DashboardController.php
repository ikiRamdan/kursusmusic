<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Course; // Asumsi model ini ada
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        if ($user->role == 'admin') {
            $data['total_users'] = User::count();
            $data['total_courses'] = DB::table('courses')->count(); // Sesuaikan nama tabel
            $data['total_mentors'] = DB::table('mentors')->count();
            $data['recent_users'] = User::latest()->take(5)->get();
        } 
        elseif ($user->role == 'owner') {
            $data['total_revenue'] = Transaction::sum('total_paid');
            $data['monthly_revenue'] = Transaction::whereMonth('created_at', date('m'))->sum('total_paid');
            $data['total_trx'] = Transaction::count();
            $data['latest_transactions'] = Transaction::latest()->take(5)->get();
        }
        elseif ($user->role == 'kasir') {
            $today = date('Y-m-d');
            $data['stats'] = [
                'total_revenue' => Transaction::whereDate('created_at', $today)->sum('total_paid'),
                'total_transactions' => Transaction::whereDate('created_at', $today)->count(),
                'new_customers' => Transaction::whereDate('created_at', $today)->distinct('customer_phone')->count(),
                'pending_payments' => Transaction::where('payment_status', 'dp')->count(),
            ];
        }

        return view($user->role . '.dashboard', $data);
    }
}