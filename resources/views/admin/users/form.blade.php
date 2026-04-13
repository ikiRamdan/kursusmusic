<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $user->name ?? '') }}">

    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email"
        class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email', $user->email ?? '') }}">

    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">
        Password
        <small class="text-muted">(kosongkan jika tidak diubah)</small>
    </label>

    <input type="password" name="password"
        class="form-control @error('password') is-invalid @enderror">

    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Role</label>
    <select name="role"
        class="form-select @error('role') is-invalid @enderror">

        <option value="">-- Pilih Role --</option>

        @foreach(['admin','owner','kasir'] as $role)
            <option value="{{ $role }}"
                {{ old('role', $user->role ?? '') == $role ? 'selected' : '' }}>
                {{ ucfirst($role) }}
            </option>
        @endforeach
    </select>

    @error('role')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status"
        class="form-select @error('status') is-invalid @enderror">

        <option value="">-- Pilih Status --</option>

        @foreach(['active','inactive'] as $status)
            <option value="{{ $status }}"
                {{ old('status', $user->status ?? '') == $status ? 'selected' : '' }}>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>

    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>