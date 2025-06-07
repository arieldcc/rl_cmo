@extends('layouts.admin')

@section('title', 'Prediksi Penjualan')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Form Prediksi Target Penjualan Bulan Berikutnya</h5>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('prediksi.hitung') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Target Bulan Sebelumnya (Rp)</label>
                <input type="number" name="target_sebelumnya" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Capaian Bulan Sebelumnya (Rp)</label>
                <input type="number" name="capaian_sebelumnya" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Potongan DP / Diskon (Rp)</label>
                <input type="number" name="potongan_dp" class="form-control" required>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Hitung Prediksi</button>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        @isset($prediksi)
            <div class="card mt-4">
                <div class="card-body">
                    <h5>Hasil Prediksi</h5>
                    @if(isset($koefisien))
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <strong>Rincian Perhitungan Regresi Linier Berganda</strong>
                            </div>
                            <div class="card-body">
                                <p><strong>Model:</strong></p>
                                <pre>Y = a + b₁·X₁ + b₂·X₂ + b₃·X₃</pre>

                                <p><strong>Koefisien:</strong></p>
                                <ul>
                                    <li><strong>a</strong> = {{ number_format($koefisien['a'], 2, ',', '.') }}</li>
                                    <li><strong>b₁</strong> = {{ number_format($koefisien['b1'], 6, ',', '.') }}</li>
                                    <li><strong>b₂</strong> = {{ number_format($koefisien['b2'], 6, ',', '.') }}</li>
                                    <li><strong>b₃</strong> = {{ number_format($koefisien['b3'], 6, ',', '.') }}</li>
                                </ul>

                                <p><strong>Substitusi nilai input:</strong></p>
                                <pre>
                    Y = {{ number_format($koefisien['a'], 2, ',', '.') }}
                    + ({{ number_format($koefisien['b1'], 6, ',', '.') }} × {{ number_format($input['target_sebelumnya'], 0, ',', '.') }})
                    + ({{ number_format($koefisien['b2'], 6, ',', '.') }} × {{ number_format($input['capaian_sebelumnya'], 0, ',', '.') }})
                    + ({{ number_format($koefisien['b3'], 6, ',', '.') }} × {{ number_format($input['potongan_dp'], 0, ',', '.') }})
                                </pre>

                                <p><strong>Hasil akhir prediksi:</strong></p>
                                <h4 class="text-success">Rp {{ number_format($prediksi, 0, ',', '.') }}</h4>
                            </div>
                        </div>

                        @if(isset($aktual))
                            <div class="mt-4">
                                <div class="alert alert-info">
                                    <strong>Data Aktual Tersedia</strong><br>
                                    <ul class="mb-1">
                                        <li><strong>Nilai Aktual Target Bulan Berikutnya:</strong> Rp {{ number_format($aktual, 0, ',', '.') }}</li>
                                        <li><strong>Prediksi:</strong> Rp {{ number_format($prediksi, 0, ',', '.') }}</li>
                                        <li><strong>MAPE:</strong> {{ number_format($mape, 2) }}%</li>
                                        <li><strong>Interpretasi Akurasi:</strong> {{ $interpretasi }}</li>
                                    </ul>
                                    <small>
                                        Rumus: MAPE = |(Aktual − Prediksi) / Aktual| × 100%
                                    </small>
                                </div>
                            </div>
                        @else
                            <div class="mt-4">
                                <div class="alert alert-warning">
                                    <strong>Data aktual tidak ditemukan</strong><br>
                                    MAPE tidak dapat dihitung karena kombinasi data belum tersedia di database.
                                </div>
                            </div>
                        @endif
                    @endif

                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Target Bulan Sebelumnya:</strong> Rp {{ number_format($input['target_sebelumnya'], 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Capaian Bulan Sebelumnya:</strong> Rp {{ number_format($input['capaian_sebelumnya'], 0, ',', '.') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Potongan DP:</strong> Rp {{ number_format($input['potongan_dp'], 0, ',', '.') }}
                        </li>
                        <li class="list-group-item bg-light">
                            <strong>Prediksi Target Bulan Berikutnya:</strong>
                            <span class="text-success fs-5">Rp {{ number_format($prediksi, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        @endisset

    </div>
</div>
@endsection
