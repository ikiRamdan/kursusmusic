@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">📜 Log Aktivitas Sistem</h3>
            <p class="text-muted small">Memantau seluruh tindakan yang dilakukan oleh Admin dan Kasir.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <form action="{{ route('owner.logs.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="small text-muted fw-bold mb-2">Cari Berdasarkan Role</label>
                <select name="role" class="form-select border-0 bg-light rounded-3">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ request('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="small text-muted fw-bold mb-2">Pilih Tanggal</label>
                <input type="date" name="date" class="form-control border-0 bg-light rounded-3" value="{{ request('date') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold rounded-3">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('owner.logs.index') }}" class="btn btn-white border fw-bold rounded-3">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive text-nowrap">
            <table class="table align-middle mb-0">
                <thead class="table-light text-uppercase small">
                    <tr>
                        <th class="ps-4 py-3">Waktu</th>
                        <th>User (Staff)</th>
                        <th>Aktivitas</th>
                        <th class="pe-4">Keterangan Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <span class="d-block fw-bold text-dark small text-uppercase">{{ $log->created_at->format('d M Y') }}</span>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at->format('H:i') }} ({{ $log->created_at->diffForHumans() }})</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div>
                                    <span class="fw-bold d-block mb-0">{{ $log->user->name ?? 'User Terhapus' }}</span>
                                    <span class="badge {{ ($log->user->role ?? '') == 'admin' ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info' }}" style="font-size: 0.65rem;">
                                        {{ strtoupper($log->user->role ?? 'N/A') }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-dark rounded-pill px-3 py-2" style="font-size: 0.7rem;">
                                {{ $log->activity }}
                            </span>
                        </td>
                        <td class="pe-4 text-wrap" style="max-width: 300px;">
                            <code class="small text-muted">{{ $log->description }}</code>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-slash-circle d-block mb-2 fs-2"></i>
                            Belum ada rekaman aktivitas yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center pagination-sm">
    {{ $logs->links('pagination::bootstrap-5') }}
</div>
</div>

<style>
    .bg-danger-subtle { background-color: #fee2e2 !important; }
    .bg-info-subtle { background-color: #e0f2fe !important; }
    .text-wrap code {
        word-break: break-all;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
    }
</style>
@endsection