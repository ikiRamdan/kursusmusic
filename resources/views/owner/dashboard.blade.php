@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="container pb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1">Analisis Bisnis Kursus</h2>
            <p class="text-muted small">Pantau pertumbuhan omzet dan tren kursus saat ini.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-dark shadow-sm rounded-pill px-4" onclick="window.print()">
                <i class="bi bi-download me-2"></i> Unduh Laporan
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white p-4 h-100">
                <h6 class="opacity-75 small text-uppercase">Total Seluruh Omzet</h6>
                <h1 class="display-5 fw-bold mb-3">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h1>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success me-2">+ 12%</span>
                    <span class="small opacity-50">Dari bulan sebelumnya</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between mb-2">
                    <h6 class="text-muted small text-uppercase mb-0">Pendapatan Bulan Ini</h6>
                </div>
                <h2 class="fw-bold text-dark">Rp {{ number_format($monthly_revenue, 0, ',', '.') }}</h2>
                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-primary" style="width: 70%"></div>
                </div>
                <small class="text-muted mt-2 d-block small">Target bulanan: Rp 50.000.000</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 px-2">Transaksi Terakhir</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Total Bayar</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latest_transactions as $lt)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">{{ $lt->invoice_code }}</td>
                                    <td>{{ $lt->customer_name }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($lt->total_paid) }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $lt->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill">
                                            {{ strtoupper($lt->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection