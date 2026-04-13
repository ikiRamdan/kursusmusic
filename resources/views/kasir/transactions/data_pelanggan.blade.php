@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold text-dark">👥 Data Pelanggan</h2>
            <p class="text-muted small">Daftar pelanggan tetap dan kursus yang diikuti.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" width="50">No</th>
                            <th>Nama Pelanggan</th>
                            <th>No. WhatsApp</th>
                            <th>Alamat</th>
                            <th>Kursus/Paket yang Diambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                        <tr>
                            <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $customer->customer_name }}</span>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->customer_phone) }}" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-whatsapp text-success me-1"></i> {{ $customer->customer_phone ?? '-' }}
                                </a>
                            </td>
                            <td>
                                <span class="small text-muted">{{ $customer->customer_address ?? 'Alamat tidak diisi' }}</span>
                            </td>
                            <td>
                                @php
                                    // Mengambil semua nama paket unik yang pernah dibeli pelanggan ini
                                    $allPackages = \App\Models\Transaction::where('customer_name', $customer->customer_name)
                                        ->where('customer_phone', $customer->customer_phone)
                                        ->with('details.package')
                                        ->get()
                                        ->pluck('details')
                                        ->flatten()
                                        ->pluck('package.name')
                                        ->unique();
                                @endphp

                                @foreach($allPackages as $packageName)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 mb-1">
                                        {{ $packageName }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people d-block mb-2 fs-2"></i>
                                Belum ada data pelanggan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }
    .table thead th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-top: 15px;
        padding-bottom: 15px;
    }
</style>
@endsection