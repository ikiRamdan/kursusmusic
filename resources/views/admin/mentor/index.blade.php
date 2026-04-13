@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h4>Mentors</h4>
        <a href="{{ route('admin.mentors.create') }}" class="btn btn-primary">
            + Tambah Mentor
        </a>
    </div>

    <form method="GET" class="row mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                placeholder="Search nama/email/phone"
                value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Search</button>
            <a href="{{ route('admin.mentors.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($mentors as $mentor)
                    <tr>
                        <td>
    @if($mentor->foto)
        <img src="{{ asset('storage/'.$mentor->foto) }}"
             width="60" class="rounded">
    @else
        -
    @endif
</td>
                        <td>{{ $mentor->name }}</td>
                        <td>{{ $mentor->email }}</td>
                        <td>{{ $mentor->phone }}</td>
                        <td>
                            <a href="{{ route('admin.mentors.edit', $mentor->id) }}"
                               class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('admin.mentors.destroy', $mentor->id) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Hapus mentor?')"
                                    class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            {{ $mentors->links() }}

        </div>
    </div>

</div>
@endsection