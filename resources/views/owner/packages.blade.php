@extends('layouts.app')

@section('title', 'Data Paket Kursus')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">📦 Paket Kursus</h2>
            <p class="text-muted small">Daftar paket bundling kursus yang tersedia.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($packages as $package)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                            {{ $package->duration_in_month }} Bulan
                        </span>
                        <h5 class="fw-bold text-success mb-0">Rp {{ number_format($package->price, 0, ',', '.') }}</h5>
                    </div>
                    
                    <h4 class="fw-bold mb-2">{{ $package->name }}</h4>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-calendar-check me-1"></i> {{ $package->session_count }} Sesi Pertemuan
                    </p>

                    <hr class="opacity-10">

                    <h6 class="fw-bold small text-uppercase text-muted mb-3">Termasuk Kursus:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($package->courses as $course)
                            <span class="badge bg-light text-dark border rounded-pill">
                                <i class="bi bi-music-note-beamed me-1"></i> {{ $course->name }}
                            </span>
                        @empty
                            <span class="text-muted small italic">Tidak ada kursus terhubung</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada paket kursus yang dibuat.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection