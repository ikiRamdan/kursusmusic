@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-sm btn-light mr-3 shadow-sm text-primary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="h3 mb-0 text-gray-800">Tambah Ruangan</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.rooms.store') }}" method="POST">
                        @csrf
                        @include('admin.room.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection