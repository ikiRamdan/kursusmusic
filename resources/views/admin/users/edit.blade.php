@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Edit User</h4>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.users.form')

        <button class="btn btn-primary mt-3">Update</button>
    </form>

</div>
@endsection