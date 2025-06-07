@extends('layouts.admin')

@section('title', 'Data Sales')

@section('custom_css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
@include('layouts.partials._message')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Data Sales</h5>
            <a href="{{ route('sales.create') }}" class="btn btn-success">+ Tambah Data</a>
        </div>
    </div>
    <div class="card-body p-1">
        <div class="mb-3 d-flex justify-content-end gap-2">
            <a href="{{ route('sales.export.excel') }}" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
            <a href="{{ route('sales.export.pdf') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Bulan</th>
                        <th>Target Sebelumnya (Rp)</th>
                        <th>Capaian Sebelumnya (Rp)</th>
                        <th>Potongan DP (Rp)</th>
                        <th>Target Berikutnya (Rp)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $row)
                    <tr>
                        <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                        <td>{{ $row->bulan }}</td>
                        <td>{{ number_format($row->target_sebelumnya, 0, ',', '.') }}</td>
                        <td>{{ number_format($row->capaian_sebelumnya, 0, ',', '.') }}</td>
                        <td>{{ number_format($row->potongan_dp, 0, ',', '.') }}</td>
                        <td>{{ number_format($row->target_berikutnya, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('sales.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('sales.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Data belum tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">
            {{ $data->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
