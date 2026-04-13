@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Admin Control Center</h2>
            <p class="text-muted small">Monitor infrastruktur dan manajemen data sistem.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                <i class="bi bi-shield-check me-1"></i> Mode Administrator
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-primary-subtle rounded-4 me-3">
                        <i class="bi bi-people-fill text-primary fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small">Total Pengguna</h6>
                        <h3 class="fw-bold mb-0">{{ $total_users }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-info-subtle rounded-4 me-3">
                        <i class="bi bi-journal-text text-info fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small">Total Kursus</h6>
                        <h3 class="fw-bold mb-0">{{ $total_courses }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-warning-subtle rounded-4 me-3">
                        <i class="bi bi-person-badge text-warning fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small">Total Mentor</h6>
                        <h3 class="fw-bold mb-0">{{ $total_mentors }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Pengguna Baru Terdaftar</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($recent_users as $r_user)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $r_user->name }}</div>
                                    <small class="text-muted">{{ $r_user->email }}</small>
                                </div>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $r_user->role }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Sistem Status</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="spinner-grow spinner-grow-sm text-light me-2" role="status"></div>
                        <span>Database Connection: <b class="text-white">Active</b></span>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-cpu me-2"></i>
                        <span>PHP Version: <b>{{ PHP_VERSION }}</b></span>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light w-100 fw-bold rounded-pill">Kelola Pengguna</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection