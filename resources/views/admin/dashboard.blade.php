@extends('layouts.admin')

@section('title', 'Dashboard')

@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-success border-5">
            <div class="card-body">
                <h5>Total Data Penjualan</h5>
                <h3>{{ $totalSales }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-info border-5">
            <div class="card-body">
                <h5>Total Data Prediksi</h5>
                <h3>{{ $totalPrediksi }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-warning border-5">
            <div class="card-body">
                <h5>Rata-rata MAPE</h5>
                <h3>
                    {{ $averageMape !== null ? $averageMape . '%' : 'N/A' }}
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="mb-3 text-end">
    <a href="{{ route('export.prediksi') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-download"></i> Export Prediksi (Excel)
    </a>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-light">
        <strong>Grafik Tren: Data Aktual vs Prediksi</strong>
    </div>
    <div class="card-body">
        <canvas id="dashboardChart" height="100"></canvas>
    </div>
</div>

{{-- Tabel prediksi terakhir --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-primary text-white">
        <strong>5 Data Prediksi Terbaru</strong>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Target Sebelumnya</th>
                    <th>Capaian</th>
                    <th>Potongan</th>
                    <th>Hasil Prediksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prediksiTerakhir as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                        <td>{{ number_format($row->target_sebelumnya, 0, ',', '.') }}</td>
                        <td>{{ number_format($row->capaian_sebelumnya, 0, ',', '.') }}</td>
                        <td>{{ number_format($row->potongan_dp, 0, ',', '.') }}</td>
                        <td class="text-primary fw-bold">{{ number_format($row->hasil_prediksi, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('custom_js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('dashboardChart');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [
            {
                label: 'Aktual',
                data: {!! json_encode($dataAktual) !!},
                borderColor: 'green',
                tension: 0.3
            },
            {
                label: 'Prediksi',
                data: {!! json_encode($dataPrediksi) !!},
                borderColor: 'blue',
                borderDash: [5, 5],
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: false } }
    }
});
</script>
@endsection

