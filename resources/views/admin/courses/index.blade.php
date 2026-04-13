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
        <h4 class="mb-0">Daftar Kursus</h4>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus"></i> Tambah Course Baru
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- Form Filter Terpadu --}}
            <form action="{{ route('admin.courses.index') }}" method="GET" class="row g-2 mb-4">
                {{-- Dropdown Nama Kursus (Atau Search) --}}
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama kursus..." value="{{ request('search') }}">
                </div>

                {{-- Dropdown Mentor --}}
                <div class="col-md-3">
                    <select name="mentor_id" class="form-control">
                        <option value="">-- Pilih Mentor --</option>
                        @foreach($mentors as $mentor)
                            <option value="{{ $mentor->id }}" {{ request('mentor_id') == $mentor->id ? 'selected' : '' }}>
                                {{ $mentor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Dropdown Status --}}
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">-- Status --</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <div class="btn-group w-100">
                       <button type="submit" class="btn btn-info btn-block shadow-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
                        @if(request()->anyFilled(['search', 'mentor_id', 'status']))
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary shadow-sm">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Foto</th>
                            <th>Nama Kursus</th>
                            <th>Mentor</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td class="text-center">
    @if($course->image)
        <img src="{{ asset('storage/' . $course->image) }}"
             width="60"
             class="img-thumbnail">
    @else
        <span class="text-muted">-</span>
    @endif
</td>
                            <td class="font-weight-bold align-middle">{{ $course->name }}</td>
                            <td class="align-middle">{{ $course->mentor->name ?? '-' }}</td>
                            <td class="text-center align-middle">
                                @if($course->status == 'available')
                                    {{-- Soft Green Badge: Teks Gelap --}}
                                    <span class="badge p-2" style="background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; min-width: 100px;">
                                        <i class="fas fa-check-circle fa-sm mr-1"></i> Available
                                    </span>
                                @else
                                    {{-- Soft Red Badge: Teks Gelap --}}
                                    <span class="badge p-2" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; min-width: 100px;">
                                        <i class="fas fa-times-circle fa-sm mr-1"></i> Unavailable
                                    </span>
                                @endif
                            </td>
                           

<td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">
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
                            <td colspan="4" class="text-center py-4 text-muted">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $courses->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection