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
        <h4 class="mb-0">Daftar Ruangan</h4>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus"></i> Tambah Ruangan Baru
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- Form Filter --}}
            <form action="{{ route('admin.rooms.index') }}" method="GET" class="row g-2 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama ruangan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-info shadow-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary shadow-sm">Reset</a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Nama Ruangan</th>
                            <th class="text-center">Kapasitas</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td class="font-weight-bold align-middle">{{ $room->name }}</td>
                            <td class="text-center align-middle">
                                {{-- Badge Kapasitas dengan warna lembut (Soft Blue) --}}
                                <span class="badge p-2" style="background-color: #e0f2ff; color: #0369a1; border: 1px solid #bae6fd; min-width: 80px;">
                                    <i class="fas fa-users fa-sm mr-1"></i> {{ $room->capacity }} Orang
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus ruangan ini?')">
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
                            <td colspan="3" class="text-center py-4 text-muted">Data ruangan tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $rooms->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection