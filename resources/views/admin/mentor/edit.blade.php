@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Mentor</h4>

  <form action="{{ route('admin.mentors.update', $mentor->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_method" value="PUT"> {{-- Pengganti @method('PUT') --}}

    @include('admin.mentor.form')

    <button type="submit" class="btn btn-success">Update Mentor</button>
</form>
</div>
@endsection