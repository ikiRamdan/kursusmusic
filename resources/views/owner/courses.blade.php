@extends('layouts.app')

@section('title', 'Data Kursus')

@section('content')
<div class="container pb-5">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">🎸 Materi Kursus</h2>
        <p class="text-muted small">Daftar seluruh alat musik/materi yang diajarkan.</p>
    </div>

    <div class="row g-4">
        @foreach($courses as $course)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                @else
                    <div class="bg-secondary-subtle d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="bi bi-music-note fs-1 text-secondary"></i>
                    </div>
                @endif
                <div class="card-body p-3">
                    <h5 class="fw-bold mb-1">{{ $course->name }}</h5>
                    <p class="text-muted small mb-2 text-truncate">{{ $course->description }}</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="small fw-bold text-primary">
                            <i class="bi bi-person-badge me-1"></i> Mentor: {{ $course->mentor->name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection