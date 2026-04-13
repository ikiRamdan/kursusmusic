<div class="row">
    <div class="col-md-6 mb-3">
        <label class="fw-bold">Kursus</label>
        <select name="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror">
            <option value="">-- Pilih Kursus --</option>
            @foreach($courses as $c)
                <option value="{{ $c->id }}" {{ old('course_id', $schedule->course_id) == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
        @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="fw-bold">Mentor</label>
        <select name="mentor_id" id="mentor_id" class="form-control @error('mentor_id') is-invalid @enderror">
            <option value="">-- Pilih Mentor --</option>
            @foreach($mentors as $m)
                <option value="{{ $m->id }}" {{ old('mentor_id', $schedule->mentor_id) == $m->id ? 'selected' : '' }}>
                    {{ $m->name }}
                </option>
            @endforeach
        </select>
        @error('mentor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small class="text-muted" id="mentor_status"></small>
    </div>
</div>

<div class="mb-3">
    <label class="fw-bold">Ruangan</label>
    <select name="room_id" class="form-control @error('room_id') is-invalid @enderror">
        <option value="">-- Pilih Ruangan --</option>
        @foreach($rooms as $r)
            <option value="{{ $r->id }}" {{ old('room_id', $schedule->room_id) == $r->id ? 'selected' : '' }}>
                {{ $r->name }} (Kapasitas: {{ $r->capacity }})
            </option>
        @endforeach
    </select>
    @error('room_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="fw-bold">Hari</label>
        <select name="day_of_week" class="form-control @error('day_of_week') is-invalid @enderror">
            <option value="">-- Pilih Hari --</option>
            @foreach($days as $day)
                <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week) == $day ? 'selected' : '' }}>
                    {{ $day }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="fw-bold">Jam Mulai</label>
        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
               value="{{ old('start_time', $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="fw-bold">Jam Selesai</label>
        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" 
               value="{{ old('end_time', $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}">
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.getElementById('course_id'); // Pastikan id di <select> adalah course_id
        const mentorSelect = document.getElementById('mentor_id'); // Pastikan id di <select> adalah mentor_id
        const statusText = document.getElementById('mentor_status');

        courseSelect.addEventListener('change', function() {
            const courseId = this.value;

            if (!courseId) {
                statusText.innerText = '';
                return;
            }

            statusText.innerText = 'Mencari mentor...';
            statusText.className = 'text-info small';

            // URL Dinamis menggunakan helper route Laravel
            const url = `{{ route('admin.get-mentors', '') }}/${courseId}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Data tidak ditemukan');
                    return response.json();
                })
                .then(mentor => {
                    if (mentor) {
                        // Set nilai dropdown mentor
                        mentorSelect.value = mentor.id;
                        statusText.innerText = `Mentor ${mentor.name} terpilih otomatis.`;
                        statusText.className = 'text-success small';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusText.innerText = 'Mentor belum diatur untuk kursus ini.';
                    statusText.className = 'text-muted small';
                    // Reset dropdown mentor jika gagal
                    mentorSelect.value = "";
                });
        });
    });
</script>