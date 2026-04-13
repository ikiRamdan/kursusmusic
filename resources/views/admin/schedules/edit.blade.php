@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h4>Edit Jadwal</h4></div>
        <div class="card-body">
            <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.schedules.form')
                <hr>
                <button type="submit" class="btn btn-success">Update Jadwal</button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection