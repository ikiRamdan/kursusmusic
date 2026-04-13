@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4" id="receipt" style="border-radius: 15px;">
                <div class="text-center mb-3">
                    <h4 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 2px;">KURSUS MUSIC</h4>
                    <p class="small text-muted mb-0">Solusi Cerdas Belajar Musik</p>
                    <p class="small text-muted">Jl. Contoh Alamat No. 123, Bandung</p>
                    <div style="border-top: 1px dashed #ccc;" class="my-3"></div>
                    <div class="bg-light p-2 small mb-3 rounded">
                        <b class="text-primary">{{ $transaction->invoice_code }}</b><br>
                        <span class="text-muted">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <table class="table table-sm table-borderless small mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Konsumen</td>
                            <td class="text-end fw-bold">{{ $transaction->customer_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">WhatsApp</td>
                            <td class="text-end">{{ $transaction->customer_phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kasir</td>
                            <td class="text-end">{{ $transaction->user->name ?? 'Admin' }}</td>
                        </tr>
                    </table>
                </div>

                <table class="table table-sm small mb-0">
                    <thead style="border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;">
                        <tr>
                            <th class="py-2">Paket Kursus</th>
                            <th class="text-end py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $detail)
                        <tr>
                            <td class="py-2">{{ $detail->package->name }}</td>
                            <td class="text-end py-2">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="border-top: 1px dashed #ccc;">
                        <tr>
                            <td class="pt-2 fw-bold">TOTAL TAGIHAN</td>
                            <td class="text-end pt-2 fw-bold text-primary" style="font-size: 1.1rem;">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="bg-light p-3 rounded-3 small mt-3">
                    <div class="d-flex justify-content-between mb-1 text-muted">
                        <span>Total Dibayar:</span>
                        <span class="fw-bold text-success">Rp {{ number_format($transaction->total_paid, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($transaction->payment_status == 'dp')
                        <div style="border-top: 1px solid #dee2e6;" class="my-2 pt-2"></div>
                        <div class="d-flex justify-content-between text-danger fw-bold" style="font-size: 0.9rem;">
                            <span>SISA PEMBAYARAN:</span>
                            <span>Rp {{ number_format($transaction->total_price - $transaction->total_paid, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-center mt-3">
                            <span class="badge bg-warning text-dark w-100 py-2 border-0">STATUS: DP (BELUM LUNAS)</span>
                        </div>
                    @else
                        <div class="d-flex justify-content-between mb-1 text-muted">
                            <span>Uang Fisik:</span>
                            <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-dark">
                            <span>Kembalian:</span>
                            <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-center mt-3">
                            <span class="badge bg-success w-100 py-2 border-0">STATUS: LUNAS</span>
                        </div>
                    @endif
                </div>

                <div class="text-center mt-4 small pt-3" style="border-top: 1px dashed #ccc;">
                    <p class="mb-1 text-muted">Harap simpan struk ini sebagai bukti pembayaran resmi.</p>
                    <p class="fw-bold text-dark">Terima kasih atas kepercayaan Anda!</p>
                </div>
                
                <div class="d-print-none mt-4 pt-2">
                    <button onclick="window.print()" class="btn btn-primary w-100 mb-2 py-2 fw-bold shadow-sm">
                        <i class="bi bi-printer me-2"></i>Cetak Struk
                    </button>
                    <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary w-100 py-2 fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling khusus struk termal agar pas di kertas */
    @media print {
        body { background: white !important; }
        .container { width: 100% !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        .row { margin: 0 !important; }
        .col-md-5 { width: 100% !important; max-width: 100% !important; padding: 0 !important; }
        body * { visibility: hidden; }
        #receipt, #receipt * { visibility: visible; }
        #receipt { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%;
            box-shadow: none !important;
            border: none !important;
            padding: 10px !important;
        }
        .d-print-none { display: none !important; }
        .card { border-radius: 0 !important; }
    }
    
    /* Font style for receipt feel */
    #receipt {
        font-family: 'Courier New', Courier, monospace;
        color: #000;
    }
</style>

<script>
    window.onload = function() {
        // Otomatis buka dialog print saat halaman dimuat
        setTimeout(() => {
            window.print();
        }, 800);
    }
</script>
@endsection