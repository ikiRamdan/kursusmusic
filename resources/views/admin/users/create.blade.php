@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Tambah User</h4>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        @include('admin.users.form')

        <button class="btn btn-primary mt-3">Simpan</button>
    </form>

</div>
@endsection