@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold text-dark">📊 Data Transaksi</h2>
            <p class="text-muted small">Kelola pendaftaran dan pelunasan paket kursus.</p>
        </div>
        <a href="{{ route('kasir.transactions.create') }}" class="btn btn-primary shadow-sm fw-bold">
            <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Invoice</th>
                            <th>Pelanggan</th>
                            <th>Total Tagihan</th>
                            <th>Dibayar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr>
                            <td class="ps-3">
                                <span class="fw-bold text-primary">{{ $trx->invoice_code }}</span>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $trx->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $trx->customer_name }}</div>
                                <div class="text-muted small">{{ $trx->customer_phone ?? '-' }}</div>
                            </td>
                            <td>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="text-success fw-bold">Rp {{ number_format($trx->total_paid, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($trx->payment_status == 'paid')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3">LUNAS</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3">
                                        DP (Sisa: Rp {{ number_format($trx->total_price - $trx->total_paid, 0, ',', '.') }})
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('kasir.transactions.show', $trx->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                    @if($trx->payment_status == 'dp')
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalLunas{{ $trx->id }}">
                                            Lunasi
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL PELUNASAN --}}
                        @if($trx->payment_status == 'dp')
                        <div class="modal fade" id="modalLunas{{ $trx->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('kasir.transactions.pay', $trx->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Pelunasan {{ $trx->invoice_code }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info py-2 small">Sisa yang harus dibayar: <strong>Rp {{ number_format($trx->total_price - $trx->total_paid, 0, ',', '.') }}</strong></div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nominal Pembayaran</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" name="payment_amount" class="form-control fw-bold" 
                                                           min="{{ $trx->total_price - $trx->total_paid }}" required placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success w-100 fw-bold">PROSES PELUNASAN</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $transactions->links() }}</div>
</div>
@endsection