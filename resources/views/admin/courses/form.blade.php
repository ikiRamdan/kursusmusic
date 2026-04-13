<div class="mb-3">
    <label>Foto Course</label>
    <input type="file" name="image"
        class="form-control @error('image') is-invalid @enderror">

    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if(!empty($course->image))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $course->image) }}"
                 width="120"
                 class="img-thumbnail">
        </div>
    @endif
</div>

<div class="mb-3">
    <label>Mentor</label>
    <select name="mentor_id"
        class="form-control @error('mentor_id') is-invalid @enderror">

        <option value="">-- Pilih Mentor --</option>

        @foreach($mentors as $mentor)
            <option value="{{ $mentor->id }}"
                {{ old('mentor_id', $course->mentor_id ?? '') == $mentor->id ? 'selected' : '' }}>
                {{ $mentor->name }}
            </option>
        @endforeach
    </select>

    @error('mentor_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Nama Course</label>
    <input type="text" name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $course->name ?? '') }}">

    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="3">{{ old('description', $course->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Status</label>
    <select name="status"
        class="form-control @error('status') is-invalid @enderror">

        <option value="available"
            {{ old('status', $course->status ?? '') == 'available' ? 'selected' : '' }}>
            Available
        </option>

        <option value="unavailable"
            {{ old('status', $course->status ?? '') == 'unavailable' ? 'selected' : '' }}>
            Unavailable
        </option>
    </select>

    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>