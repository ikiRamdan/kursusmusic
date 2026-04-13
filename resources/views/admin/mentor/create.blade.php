@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Tambah Mentor</h4>

    <form action="{{ route('admin.mentors.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        @include('admin.mentor.form')

        <button class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>
@endsection