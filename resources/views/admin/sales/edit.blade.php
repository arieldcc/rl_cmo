@extends('layouts.admin')

@section('title', 'Edit Data Sales')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-white">
        <h5 class="mb-0">Edit Data Sales</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('sales.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
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
                        <option value="{{ $bulan }}" {{ old('bulan', $data->bulan) == $bulan ? 'selected' : '' }}>
                            {{ $bulan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="target_sebelumnya" class="form-label">Target Sebelumnya (Rp)</label>
                <input type="number" name="target_sebelumnya" class="form-control" value="{{ old('target_sebelumnya', $data->target_sebelumnya) }}" required>
            </div>
            <div class="mb-3">
                <label for="capaian_sebelumnya" class="form-label">Capaian Sebelumnya (Rp)</label>
                <input type="number" name="capaian_sebelumnya" class="form-control" value="{{ old('capaian_sebelumnya', $data->capaian_sebelumnya) }}" required>
            </div>
            <div class="mb-3">
                <label for="potongan_dp" class="form-label">Potongan DP (Rp)</label>
                <input type="number" name="potongan_dp" class="form-control" value="{{ old('potongan_dp', $data->potongan_dp) }}" required>
            </div>
            <div class="mb-3">
                <label for="target_berikutnya" class="form-label">Target Berikutnya (Rp)</label>
                <input type="number" name="target_berikutnya" class="form-control" value="{{ old('target_berikutnya', $data->target_berikutnya) }}" required>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
