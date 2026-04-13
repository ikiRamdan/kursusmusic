<div class="mb-3">
    <label class="form-label font-weight-bold">Pilih Kursus (Bisa lebih dari 1)</label>
    <select name="course_ids[]" class="form-control @error('course_ids') is-invalid @enderror" multiple style="height: 150px;" required>
        @foreach($courses as $course)
            <option value="{{ $course->id }}" 
                @if(isset($course_package))
                    {{ in_array($course->id, old('course_ids', $course_package->courses->pluck('id')->toArray())) ? 'selected' : '' }}
                @else
                    {{ (is_array(old('course_ids')) && in_array($course->id, old('course_ids'))) ? 'selected' : '' }}
                @endif
            >
                {{ $course->name }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">Tahan tombol <strong>Ctrl</strong> (Windows) atau <strong>Command</strong> (Mac) untuk memilih lebih dari satu.</small>
    @error('course_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label font-weight-bold">Nama Paket</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
           placeholder="Contoh: Paket Bundling Piano & Biola"
           value="{{ old('name', $course_package->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label font-weight-bold">Durasi (Bulan)</label>
        <input type="number" name="duration_in_month" class="form-control" 
               value="{{ old('duration_in_month', $course_package->duration_in_month ?? '') }}" placeholder="0">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label font-weight-bold">Jumlah Sesi</label>
        <input type="number" name="session_count" class="form-control" 
               value="{{ old('session_count', $course_package->session_count ?? 4) }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label font-weight-bold">Harga Total (Rp)</label>
    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
           value="{{ old('price', $course_package->price ?? '') }}" required placeholder="0">
    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>