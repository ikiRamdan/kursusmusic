@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="container pb-5">
    {{-- HEADER & NAVIGASI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary btn-sm me-3 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <div>
                <h3 class="mb-0 fw-bold text-dark">🧾 Kasir Kursus Musik</h3>
                <p class="text-muted small mb-0">Manajemen pendaftaran siswa dan paket kursus.</p>
            </div>
        </div>
        <div class="text-end">
            <span class="badge bg-primary px-3 py-2 shadow-sm">📅 {{ date('d M Y') }}</span>
        </div>
    </div>

    {{-- ALERT NOTIFIKASI --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('kasir.transactions.store') }}" method="POST" id="transaction-form">
        @csrf

        <div class="row">
            {{-- KOLOM KIRI: KATALOG PAKET --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Katalog Paket Kursus</h5>
                    </div>
                    <div class="card-body bg-light" style="max-height: 750px; overflow-y: auto;">
                        <div class="row g-3">
                            @foreach($packages as $pkg)
                            @php
                                $minQuota = 999;
                                $hasSchedule = false;
                                foreach($pkg->items as $item) {
                                    if($item->course && $item->course->schedules->count() > 0) {
                                        $hasSchedule = true;
                                        foreach($item->course->schedules as $sch) {
                                            if($sch->capacity < $minQuota) $minQuota = $sch->capacity;
                                        }
                                    }
                                }
                                $isFull = ($hasSchedule && $minQuota <= 0);
                            @endphp

                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm package-card transition-all {{ $isFull ? 'opacity-75' : '' }}">
                                    <div class="card-body d-flex flex-column">
                                        {{-- Judul & Harga --}}
                                        <div class="mb-3">
                                            <h6 class="fw-bold text-dark mb-1">{{ $pkg->name }}</h6>
                                            <h5 class="text-primary fw-bold mb-0">Rp {{ number_format($pkg->price, 0, ',', '.') }}</h5>
                                        </div>
                                        
                                        {{-- Deskripsi Durasi & Sesi --}}
                                        <div class="bg-white border rounded-3 p-2 mb-3 shadow-sm">
                                            <div class="row g-0 text-center">
                                                <div class="col-6 border-end">
                                                    <small class="text-muted d-block" style="font-size: 0.6rem;">AKTIF</small>
                                                    <span class="fw-bold small">{{ $pkg->duration_in_month }} Bulan</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block" style="font-size: 0.6rem;">TOTAL</small>
                                                    <span class="fw-bold small">{{ $pkg->session_count }}x Pertemuan</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- LIST KURSUS DALAM PAKET INI --}}
                                        <div class="mb-3">
                                            <p class="small fw-bold text-muted mb-2" style="font-size: 0.75rem;">Materi Kursus:</p>
                                            <ul class="list-unstyled mb-0">
                                                @foreach($pkg->items as $item)
                                                <li class="d-flex align-items-center mb-1 small text-dark">
                                                    <i class="bi bi-music-note-beamed text-primary me-2" style="font-size: 0.8rem;"></i>
                                                    {{ $item->course->name ?? 'Kursus Tidak Ditemukan' }}
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        {{-- Info Jadwal Ringkas --}}
                                        <div class="mb-3 flex-grow-1 border-top pt-2">
                                            <p class="small text-muted mb-1" style="font-size: 0.7rem;"><i class="bi bi-calendar-event me-1"></i> Hari:</p>
                                            @if($hasSchedule)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @php
                                                        $uniqueDays = [];
                                                        foreach($pkg->items as $item) {
                                                            if($item->course) {
                                                                foreach($item->course->schedules as $sch) {
                                                                    $hari = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
                                                                    $dayName = $hari[$sch->day_of_week] ?? $sch->day_of_week;
                                                                    if(!in_array($dayName, $uniqueDays)) $uniqueDays[] = $dayName;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @foreach($uniqueDays as $day)
                                                        <span class="badge bg-light text-dark border fw-normal" style="font-size: 0.65rem;">{{ $day }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-warning small" style="font-size: 0.65rem;"><i>Jadwal menyusul</i></span>
                                            @endif
                                        </div>

                                        {{-- Tombol --}}
                                        @if($isFull)
                                            <button type="button" class="btn btn-sm btn-outline-danger w-100 disabled" disabled>🚫 Penuh</button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary w-100 select-package fw-bold py-2" 
                                                    data-id="{{ $pkg->id }}" 
                                                    data-price="{{ $pkg->price }}"
                                                    data-name="{{ $pkg->name }}">
                                                ➕ Pilih Paket
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: DETAIL PEMBAYARAN --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg sticky-top" style="top: 20px; z-index: 1020;">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-cart-check me-2"></i>Checkout</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Informasi Siswa</label>
                            <input type="text" name="customer_name" class="form-control mb-2" placeholder="Nama Lengkap" required>
                            <input type="number" name="customer_phone" class="form-control mb-2" placeholder="No. WhatsApp" required>
                            <textarea name="customer_address" class="form-control" placeholder="Alamat" rows="2" required></textarea>
                        </div>

                        <hr>

                        <label class="form-label fw-bold small text-uppercase text-muted">Rincian Pesanan</label>
                        <div id="selected-list" class="mb-3">
                            <div class="text-center py-4 border border-dashed rounded bg-light" id="empty-msg">
                                <span class="text-muted small">Belum ada paket yang dipilih</span>
                            </div>
                        </div>
                        <div id="hidden-inputs"></div>

                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Total Biaya:</span>
                                <span class="h5 mb-0 fw-bold text-primary" id="total-text">Rp 0</span>
                            </div>
                            <div class="mt-3">
                                <label class="form-label small fw-bold">Bayar Sekarang (Tunai)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">Rp</span>
                                    <input type="number" name="payment_amount" id="payment" class="form-control fw-bold" placeholder="0">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                <span class="text-muted small" id="label-change">Kembalian:</span>
                                <span class="fw-bold" id="change-text">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" id="btn-submit" class="btn btn-secondary btn-lg w-100 py-3 fw-bold" disabled>
                            Pilih Paket Terlebih Dahulu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .package-card { cursor: pointer; border: 1px solid #eef0f2 !important; border-radius: 15px; overflow: hidden; }
    .package-card.active { border: 2px solid #0d6efd !important; background-color: #f8fbff; transform: scale(1.02); }
    .transition-all { transition: all 0.2s ease-in-out; }
    .border-dashed { border-style: dashed !important; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<script>
    let selectedPackages = [];
    const paymentInput = document.getElementById('payment');
    const totalText = document.getElementById('total-text');
    const changeText = document.getElementById('change-text');
    const labelChange = document.getElementById('label-change');
    const selectedList = document.getElementById('selected-list');
    const hiddenInputs = document.getElementById('hidden-inputs');
    const emptyMsg = document.getElementById('empty-msg');
    const btnSubmit = document.getElementById('btn-submit');

    document.querySelectorAll('.select-package').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const price = parseInt(this.dataset.price);
            const name = this.dataset.name;
            const card = this.closest('.package-card');

            const index = selectedPackages.findIndex(p => p.id === id);

            if (index > -1) {
                selectedPackages.splice(index, 1);
                this.classList.replace('btn-success', 'btn-outline-primary');
                this.innerHTML = '➕ Pilih Paket';
                card.classList.remove('active');
            } else {
                selectedPackages.push({ id, price, name });
                this.classList.replace('btn-outline-primary', 'btn-success');
                this.innerHTML = '✔ Dipilih';
                card.classList.add('active');
            }
            updateUI();
        });
    });

    function updateUI() {
        let total = 0;
        selectedList.innerHTML = '';
        hiddenInputs.innerHTML = '';

        if (selectedPackages.length === 0) {
            selectedList.appendChild(emptyMsg);
        } else {
            selectedPackages.forEach(p => {
                total += p.price;
                const item = document.createElement('div');
                item.className = 'd-flex justify-content-between small mb-2 p-2 bg-white border rounded shadow-sm animate__animated animate__fadeIn';
                item.innerHTML = `<span><i class="bi bi-check-circle-fill text-success me-2"></i>${p.name}</span> <strong>Rp ${p.price.toLocaleString('id-ID')}</strong>`;
                selectedList.appendChild(item);
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'packages[]';
                input.value = p.id;
                hiddenInputs.appendChild(input);
            });
        }
        totalText.innerText = 'Rp ' + total.toLocaleString('id-ID');
        validateTransaction(total);
    }

    function validateTransaction(total) {
        const bayar = parseInt(paymentInput.value) || 0;
        if (total === 0) {
            btnSubmit.disabled = true;
            btnSubmit.className = "btn btn-secondary btn-lg w-100 py-3 fw-bold";
            btnSubmit.innerText = "Pilih Paket Terlebih Dahulu";
            return;
        }

        if (bayar >= total) {
            const kembali = bayar - total;
            labelChange.innerText = "Kembalian:";
            changeText.innerText = 'Rp ' + kembali.toLocaleString('id-ID');
            changeText.className = 'fw-bold text-success';
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-printer me-2"></i>Simpan & Cetak Lunas';
            btnSubmit.className = "btn btn-success btn-lg w-100 py-3 fw-bold shadow";
        } else if (bayar > 0) {
            const sisa = total - bayar;
            labelChange.innerText = "Sisa (DP):";
            changeText.innerText = 'Rp ' + sisa.toLocaleString('id-ID');
            changeText.className = 'fw-bold text-warning';
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-clock-history me-2"></i>Simpan Pembayaran DP';
            btnSubmit.className = "btn btn-warning btn-lg w-100 py-3 fw-bold shadow";
        } else {
            changeText.innerText = 'Rp 0';
            changeText.className = 'fw-bold text-danger';
            btnSubmit.disabled = true;
            btnSubmit.innerText = "Masukkan Nominal Pembayaran";
            btnSubmit.className = "btn btn-secondary btn-lg w-100 py-3 fw-bold";
        }
    }

    paymentInput.addEventListener('input', () => {
        const total = selectedPackages.reduce((sum, p) => sum + p.price, 0);
        validateTransaction(total);
    });
</script>
@endsection