@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Course</h4>

    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.courses.form')

    <button class="btn btn-primary mt-3">Update</button>
</form>
</div>
@endsection