@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Paket Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.course-packages.store') }}" method="POST">
                @csrf
                @include('admin.course_packages.form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Paket</button>
                    <a href="{{ route('admin.course-packages.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection