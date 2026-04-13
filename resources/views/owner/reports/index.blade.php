@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 d-print-none">
        <h4 class="fw-bold"><i class="bi bi-filter me-2"></i>Filter Laporan</h4>
        <form action="{{ route('owner.reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="small text-muted">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label class="small text-muted">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Tampilkan</button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark">Hasil Laporan</h5>
                <small class="text-muted d-print-none">Centang transaksi yang ingin dicetak</small>
            </div>
            <div class="d-print-none">
                <button onclick="printSelected()" class="btn btn-dark btn-sm rounded-pill px-3">
                    <i class="bi bi-printer me-1"></i> Cetak Terpilih
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="reportTable">
                <thead class="table-light">
                    <tr>
                        <th class="text-center d-print-none" width="50">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                        </th>
                        <th class="ps-4">Invoice</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Dibayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr class="transaction-row">
                        <td class="text-center d-print-none">
                            <input type="checkbox" class="form-check-input item-checkbox" data-paid="{{ $trx->total_paid }}">
                        </td>
                        <td class="ps-4 fw-bold">{{ $trx->invoice_code }}</td>
                        <td>{{ $trx->customer_name }}</td>
                        <td>Rp {{ number_format($trx->total_price) }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($trx->total_paid) }}</td>
                        <td>
                            <span class="badge {{ $trx->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ strtoupper($trx->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Data tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-primary text-white p-2">
            <div class="d-flex justify-content-between">
                <h5 class="mb-0">TOTAL PENDAPATAN:</h5>
                <h6 class="fw-bold mb-0" id="printTotal">Rp {{ number_format($total_revenue) }}</h6>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Sembunyikan semua baris transaksi dulu */
        .transaction-row { display: none !important; }
        /* Hanya tampilkan baris yang ditandai class 'print-me' */
        .print-me { display: table-row !important; }
        
        .d-print-none { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .card-footer { background-color: #0d6efd !important; color: white !important; -webkit-print-color-adjust: exact; }
    }
</style>

<script>
    // Fitur Check All
    document.getElementById('checkAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        calculateTotal();
    });

    // Hitung total otomatis saat checkbox berubah
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', calculateTotal);
    });

    function calculateTotal() {
        let total = 0;
        let selected = document.querySelectorAll('.item-checkbox:checked');
        
        selected.forEach(cb => {
            total += parseFloat(cb.getAttribute('data-paid'));
        });

        document.getElementById('printTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function printSelected() {
        let checkboxes = document.querySelectorAll('.item-checkbox');
        let hasSelection = false;

        checkboxes.forEach(cb => {
            let row = cb.closest('tr');
            if (cb.checked) {
                row.classList.add('print-me'); // Tandai untuk dicetak
                hasSelection = true;
            } else {
                row.classList.remove('print-me');
            }
        });

        if (!hasSelection) {
            alert('Silakan pilih minimal satu transaksi untuk dicetak!');
            return;
        }

        window.print();
    }
</script>
@endsection