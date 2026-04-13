<div class="form-group mb-3">
    <label class="form-label font-weight-bold">Nama Ruangan</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
           placeholder="Contoh: Studio Piano A" value="{{ old('name', $room->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group mb-4">
    <label class="form-label font-weight-bold">Kapasitas (Orang)</label>
    <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" 
           placeholder="Masukkan jumlah kapasitas" value="{{ old('capacity', $room->capacity ?? '1') }}" min="1" required>
    @error('capacity')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<hr>
<div class="d-flex justify-content-end">
    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary mr-2">Batal</a>
    <button type="submit" class="btn btn-primary px-4">
        <i class="fas fa-save mr-1"></i> Simpan Data Ruangan
    </button>
</div>