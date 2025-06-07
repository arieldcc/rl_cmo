<?php

namespace App\Exports;

use App\Models\PredictionResult;
use Maatwebsite\Excel\Concerns\FromCollection;

class PredictionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PredictionResult::select('created_at', 'target_sebelumnya', 'capaian_sebelumnya', 'potongan_dp', 'hasil_prediksi')->latest()->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Target Sebelumnya', 'Capaian', 'Potongan DP', 'Hasil Prediksi'];
    }
}
