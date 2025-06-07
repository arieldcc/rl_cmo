@extends('layouts.admin')

@section('title', 'Tambah Data Sales')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Tambah Data Sales</h5>
            <form action="{{ route('sales.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <input type="file" name="file" class="form-control" required>
                    <button type="submit" class="btn btn-info">Import</button>
                </div>
            </form>
        </div>

    </div>
    <div class="card-body">
        <form action="{{ route('sales.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" class="form-select" required>
                @php
                    $bulanList = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                @endphp

                <option value="" disabled selected>Pilih Bulan</option>
                @foreach ($bulanList as $bulan)
                    <option value="{{ $bulan }}" {{ old('bulan') == $bulan ? 'selected' : '' }}>
                        {{ $bulan }}
                    </option>
                @endforeach
            </select>

            </div>
            <div class="mb-3">
                <label for="target_sebelumnya" class="form-label">Target Sebelumnya (Rp)</label>
                <input type="number" name="target_sebelumnya" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="capaian_sebelumnya" class="form-label">Capaian Sebelumnya (Rp)</label>
                <input type="number" name="capaian_sebelumnya" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="potongan_dp" class="form-label">Potongan DP (Rp)</label>
                <input type="number" name="potongan_dp" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="target_berikutnya" class="form-label">Target Berikutnya (Rp)</label>
                <input type="number" name="target_berikutnya" class="form-control" required>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
