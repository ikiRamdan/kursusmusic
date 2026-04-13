@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h4>Tambah Jadwal</h4></div>
        <div class="card-body">
            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf
                @include('admin.schedules.form')
                <hr>
                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection