<?php

namespace App\Exports;

use App\Models\SalesData;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesDataExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         return SalesData::select('bulan', 'target_sebelumnya', 'capaian_sebelumnya', 'potongan_dp', 'target_berikutnya')->get();
    }

    public function headings(): array
    {
        return ['Bulan', 'Target Sebelumnya', 'Capaian Sebelumnya', 'Potongan DP', 'Target Berikutnya'];
    }
}
