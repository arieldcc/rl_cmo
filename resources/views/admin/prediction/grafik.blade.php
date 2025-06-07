@extends('layouts.admin')

@section('title', 'Grafik Prediksi vs Aktual')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Perbandingan Data Aktual vs Hasil Prediksi</h5>
    </div>
    <div class="card-body">
        @if(isset($mape))
            <div class="alert alert-info">
                <strong>MAPE (Mean Absolute Percentage Error):</strong> {{ $mape }}%
                <br>
                <small>
                    Rumus: MAPE = (1/n) × ∑(|Aktual - Prediksi| / Aktual) × 100%
                </small>
                <br>
                <strong>Interpretasi Akurasi:</strong> {{ $interpretasi }}
            </div>
        @endif

        <canvas id="chartPrediksi" height="100"></canvas>
    </div>
</div>
@endsection

@section('custom_js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($labels) !!};
    const dataAktual = {!! json_encode($dataAktual) !!};
    const dataPrediksi = {!! json_encode($dataPrediksi) !!};

    new Chart(document.getElementById('chartPrediksi'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Aktual',
                    data: dataAktual,
                    borderColor: 'green',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Prediksi',
                    data: dataPrediksi,
                    borderColor: 'blue',
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
</script>
@endsection
