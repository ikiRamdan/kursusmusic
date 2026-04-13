<div class="mb-3">
    <label>Nama</label>
    <input type="text" name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $mentor->name ?? '') }}">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email"
        class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email', $mentor->email ?? '') }}">
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label>Phone</label>
    <input type="text" name="phone"
        class="form-control @error('phone') is-invalid @enderror"
        value="{{ old('phone', $mentor->phone ?? '') }}">
</div>


<div class="mb-3">
    <label>Foto</label>
    <input type="file" name="foto"
        class="form-control @error('foto') is-invalid @enderror">

    @error('foto')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    {{-- preview --}}
    @if(!empty($mentor->foto))
        <img src="{{ asset('storage/'.$mentor->foto) }}"
             width="120" class="mt-2 rounded">
    @endif
</div>
