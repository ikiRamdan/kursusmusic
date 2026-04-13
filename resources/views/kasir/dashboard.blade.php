@extends('layouts.app')

@section('title', 'Kasir Dashboard')

@section('content')
<div class="container pb-5">
    {{-- Header Welcome --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white p-4 rounded-4 overflow-hidden position-relative">
                <div class="row align-items-center position-relative" style="z-index: 2;">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                        <p class="mb-0 opacity-75">Siap untuk melayani pendaftaran kursus hari ini? Berikut ringkasan aktivitasmu.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <!-- <a href="{{ route('kasir.transactions.create') }}" class="btn btn-light fw-bold px-4 rounded-pill shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
                        </a> -->
                    </div>
                </div>
                {{-- Dekorasi Abstract Background --}}
                <div class="position-absolute top-0 end-0 opacity-10 mt-n4 me-n4">
                    <i class="bi bi-music-note-beamed" style="font-size: 10rem;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-3 bg-success-subtle rounded-3">
                            <i class="bi bi-wallet2 text-success fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small uppercase fw-bold">Pendapatan Hari Ini</h6>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-3 bg-primary-subtle rounded-3">
                            <i class="bi bi-cart-check text-primary fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small uppercase fw-bold">Total Transaksi</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['total_transactions'] ?? 0 }} <small class="text-muted small fs-6">Sesi</small></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-3 bg-info-subtle rounded-3">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small uppercase fw-bold">Pelanggan Baru</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['new_customers'] ?? 0 }} <small class="text-muted small fs-6">Orang</small></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-3 bg-warning-subtle rounded-3">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small uppercase fw-bold">Menunggu Pelunasan</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['pending_payments'] ?? 0 }} <small class="text-muted small fs-6">Tagihan</small></h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Action & Info --}}
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="fw-bold mb-0">Alur Kerja Kasir</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded-4 bg-light h-100">
                                <h1 class="display-6 fw-bold text-primary">1</h1>
                                <h6 class="fw-bold">Input Order</h6>
                                <p class="small text-muted mb-0">Pilih paket kursus yang diinginkan pelanggan.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded-4 bg-light h-100">
                                <h1 class="display-6 fw-bold text-primary">2</h1>
                                <h6 class="fw-bold">Terima Bayar</h6>
                                <p class="small text-muted mb-0">Bisa bayar Lunas atau DP terlebih dahulu.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded-4 bg-light h-100">
                                <h1 class="display-6 fw-bold text-primary">3</h1>
                                <h6 class="fw-bold">Cetak Struk</h6>
                                <p class="small text-muted mb-0">Berikan struk resmi kepada pelanggan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden h-100">
                <div class="card-body p-4 position-relative">
                    <h5 class="fw-bold mb-3">Butuh Bantuan?</h5>
                    <p class="small opacity-75">Jika terjadi kendala pada sistem cetak atau database, hubungi tim IT atau Owner.</p>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-pill px-3">
                        <i class="bi bi-telephone me-1"></i> Hubungi Owner
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary { background-color: #6366f1 !important; }
    .bg-primary-subtle { background-color: #eef2ff !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-info-subtle { background-color: #ecfeff !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; }
    .text-primary { color: #6366f1 !important; }
    .rounded-4 { border-radius: 1rem !important; }
</style>
@endsection