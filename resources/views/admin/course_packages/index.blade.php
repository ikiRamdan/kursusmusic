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
           href="{{ route('admin.courses.index') }}">
            Daftar Kursus
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.course-packages.*') ? 'active font-weight-bold' : '' }}"
           href="{{ route('admin.course-packages.index') }}">
            Paket Kursus
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active font-weight-bold' : '' }}"
           href="{{ route('admin.rooms.index') }}">
            Manajemen Ruangan
        </a>
    </li>
    <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active font-weight-bold' : '' }}"
               href="{{ route('admin.schedules.index') }}">Manajemen Jadwal</a>
        </li>
</ul>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Paket Kursus</h4>
        <a href="{{ route('admin.course-packages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Paket Baru
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
           <form action="{{ route('admin.course-packages.index') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" 
               placeholder="Cari nama paket..." value="{{ request('search') }}">
    </div>

    <div class="col-md-4">
        {{-- Name harus 'course_id' agar cocok dengan $request->course_id di Controller --}}
        <select name="course_id" class="form-control">
            <option value="">-- Semua Kursus --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <button type="submit" class="btn btn-info btn-block shadow-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>

    @if(request()->anyFilled(['search', 'course_id']))
        <div class="col-md-2">
            <a href="{{ route('admin.course-packages.index') }}" class="btn btn-secondary btn-block shadow-sm">
                Reset
            </a>
        </div>
    @endif
</form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>Nama Paket</th>
                            <th>Kursus Terkait</th>
                            <th class="text-center">Durasi</th>
                            <th class="text-center">Sesi</th>
                            <th>Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr>
                            <td class="font-weight-bold">{{ $package->name }}</td>
                            <td>
                                @foreach($package->courses as $c)
                                   <span class="badge badge-secondary p-2 mb-1" style="background-color: #e9ecef; color: #495057;">{{ $c->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">{{ $package->duration_in_month }} Bulan</td>
                            <td class="text-center">{{ $package->session_count }} Sesi</td>
                            <td class="text-success font-weight-bold">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.course-packages.edit', $package->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.course-packages.destroy', $package->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                <em>Data tidak ditemukan.</em>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $packages->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection