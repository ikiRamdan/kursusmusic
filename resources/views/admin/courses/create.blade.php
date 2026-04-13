@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Course</h4>

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @include('admin.courses.form')

    <button class="btn btn-primary mt-3">Simpan</button>
</form>
</div>
@endsection