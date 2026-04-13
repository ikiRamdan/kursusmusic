@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Kursus & Paket</h1>
    </div>

    {{-- Navigasi Tab --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active font-weight-bold' : '' }}"
               href="{{ route('admin.courses.index') }}">Daftar Kursus</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.course-packages.*') ? 'active font-weight-bold' : '' }}"
               href="{{ route('admin.course-packages.index') }}">Paket Kursus</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active font-weight-bold' : '' }}"
               href="{{ route('admin.rooms.index') }}">Manajemen Ruangan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active font-weight-bold' : '' }}"
               href="{{ route('admin.schedules.index') }}">Manajemen Jadwal</a>
        </li>
    </ul>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 text-gray-800">Daftar Jadwal</h4>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus"></i> Tambah Jadwal Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- Form Filter --}}
            <form action="{{ route('admin.schedules.index') }}" method="GET" class="row g-2 mb-4">
                <div class="col-md-3">
                    <select name="day" class="form-control">
                        <option value="">-- Semua Hari --</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="mentor_id" class="form-control">
                        <option value="">-- Semua Mentor --</option>
                        @foreach($mentors as $m)
                            <option value="{{ $m->id }}" {{ request('mentor_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="room_id" class="form-control">
                        <option value="">-- Semua Ruangan --</option>
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}" {{ request('room_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-info shadow-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        @if(request('day') || request('mentor_id') || request('room_id'))
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary shadow-sm">Reset</a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Kursus</th>
                            <th>Mentor</th>
                            <th class="text-center">Ruangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $s)
                        <tr>
                            <td class="font-weight-bold align-middle text-primary">{{ $s->day_of_week }}</td>
                            <td class="align-middle">
                                <i class="far fa-clock fa-sm mr-1"></i>
                                {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                            </td>
                            <td class="align-middle">{{ $s->course->name ?? '-' }}</td>
                            <td class="align-middle">{{ $s->mentor->name ?? '-' }}</td>
                            <td class="text-center align-middle">
                                <span class="badge p-2" style="background-color: #e0f2ff; color: #0369a1; border: 1px solid #bae6fd; min-width: 80px;">
                                    <i class="fas fa-door-open fa-sm mr-1"></i> {{ $s->room->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.schedules.edit', $s->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Data jadwal tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $schedules->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection