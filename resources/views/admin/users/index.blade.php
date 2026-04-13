@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h4>Users</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            + Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

        <form method="GET" class="row mb-3">

    <div class="col-md-3">
        <input type="text" name="search" class="form-control"
            placeholder="Search nama / email"
            value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <select name="role" class="form-control">
            <option value="">-- Role --</option>
            @foreach(['admin','owner','kasir'] as $role)
                <option value="{{ $role }}"
                    {{ request('role') == $role ? 'selected' : '' }}>
                    {{ $role }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">-- Status --</option>
            @foreach(['active','inactive'] as $status)
                <option value="{{ $status }}"
                    {{ request('status') == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->status }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Hapus user?')" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            {{ $users->links() }}

        </div>
    </div>

</div>
@endsection