<?php

namespace App\Http\Controllers;

use App\Models\PredictionResult;
use App\Models\SalesData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $totalSales = SalesData::count();
        $totalPrediksi = PredictionResult::count();
        $prediksiTerakhir = PredictionResult::latest()->take(5)->get();

        // Hitung rata-rata MAPE
        $mapeList = [];
        $sales = SalesData::all();
        $koef = app(PredictionController::class)->calculateCoefficients();

        if ($koef) {
            [$a, $b1, $b2, $b3] = $koef;
            foreach ($sales as $row) {
                $prediksi = $a + $b1 * $row->target_sebelumnya + $b2 * $row->capaian_sebelumnya + $b3 * $row->potongan_dp;
                if ($row->target_berikutnya != 0) {
                    $mapeList[] = abs(($row->target_berikutnya - $prediksi) / $row->target_berikutnya);
                }
            }
        }

        $averageMape = count($mapeList) > 0 ? round(array_sum($mapeList) / count($mapeList) * 100, 2) : null;

        $labels = [];
        $dataAktual = [];
        $dataPrediksi = [];

        if ($koef) {
            [$a, $b1, $b2, $b3] = $koef;

            foreach ($sales as $row) {
                $labels[] = $row->bulan;
                $dataAktual[] = $row->target_berikutnya;
                $prediksi = $a + $b1 * $row->target_sebelumnya + $b2 * $row->capaian_sebelumnya + $b3 * $row->potongan_dp;
                $dataPrediksi[] = $prediksi;
            }
        }

        return view('admin.dashboard', compact('totalSales', 'totalPrediksi', 'averageMape', 'prediksiTerakhir', 'labels', 'dataAktual', 'dataPrediksi'));
    }
}
