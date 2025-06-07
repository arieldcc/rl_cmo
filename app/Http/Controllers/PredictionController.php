<?php

namespace App\Http\Controllers;

use App\Models\PredictionResult;
use App\Models\SalesData;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function index(){
        return view('admin.prediction.index');
    }

    public function predict(Request $request){
        $request->validate([
            'target_sebelumnya' => 'required|numeric',
            'capaian_sebelumnya' => 'required|numeric',
            'potongan_dp' => 'required|numeric',
        ]);

        $X1 = $request->target_sebelumnya;
        $X2 = $request->capaian_sebelumnya;
        $X3 = $request->potongan_dp;

        // Hitung koefisien berdasarkan data historis
        $koef = $this->calculateCoefficients();

        if (!$koef) {
            return redirect()->route('prediksi.index')->with('error', 'Data historis tidak mencukupi untuk prediksi.');
        }

        // Koefisien regresi
        [$a, $b1, $b2, $b3] = $koef;

        // Hitung hasil prediksi
        $Y = $a + ($b1 * $X1) + ($b2 * $X2) + ($b3 * $X3);

        // Cari data aktual dengan kombinasi input
        $matchingData = SalesData::where('target_sebelumnya', $X1)
            ->where('capaian_sebelumnya', $X2)
            ->where('potongan_dp', $X3)
            ->first();

        $mape = null;
        $aktual = null;

        if ($matchingData && $matchingData->target_berikutnya != 0) {
            $aktual = $matchingData->target_berikutnya;
            $mape = abs(($aktual - $Y) / $aktual) * 100;
        }

        $interpretasi = null;
        if ($mape !== null) {
            if ($mape < 10) {
                $interpretasi = 'Sangat Baik';
            } elseif ($mape < 20) {
                $interpretasi = 'Baik';
            } elseif ($mape < 50) {
                $interpretasi = 'Cukup';
            } else {
                $interpretasi = 'Buruk';
            }
        }


        $pred = new PredictionResult();
        $pred->target_sebelumnya = $X1;
        $pred->capaian_sebelumnya = $X2;
        $pred->potongan_dp = $X3;
        $pred->hasil_prediksi = $Y;
        $pred->target_prediksi = $aktual;
        $pred->mape = $mape;
        $pred->save();

        // Kembalikan ke view dengan data
        return view('admin.prediction.index', [
                    'prediksi' => $Y,
                    'input' => [
                        'target_sebelumnya' => $X1,
                        'capaian_sebelumnya' => $X2,
                        'potongan_dp' => $X3,
                    ],
                    'koefisien' => [
                        'a' => $a,
                        'b1' => $b1,
                        'b2' => $b2,
                        'b3' => $b3,
                    ],
                    'aktual' => $aktual,
                    'mape' => $mape,
                    'interpretasi' => $interpretasi
                ])->with('success', 'Prediksi berhasil dihitung.');
    }

    public function calculateCoefficients(){
        $data = SalesData::all();

        $n = $data->count();
        if ($n < 3) {
            return null; // Minimal 3 data
        }

        // Inisialisasi jumlah total
        $sumX1 = $sumX2 = $sumX3 = $sumY = 0;
        $sumX1_2 = $sumX2_2 = $sumX3_2 = 0;
        $sumX1X2 = $sumX1X3 = $sumX2X3 = 0;
        $sumX1Y = $sumX2Y = $sumX3Y = 0;

        foreach ($data as $row) {
            $x1 = $row->target_sebelumnya;
            $x2 = $row->capaian_sebelumnya;
            $x3 = $row->potongan_dp;
            $y  = $row->target_berikutnya;

            $sumX1 += $x1;
            $sumX2 += $x2;
            $sumX3 += $x3;
            $sumY  += $y;

            $sumX1_2 += $x1 * $x1;
            $sumX2_2 += $x2 * $x2;
            $sumX3_2 += $x3 * $x3;

            $sumX1X2 += $x1 * $x2;
            $sumX1X3 += $x1 * $x3;
            $sumX2X3 += $x2 * $x3;

            $sumX1Y += $x1 * $y;
            $sumX2Y += $x2 * $y;
            $sumX3Y += $x3 * $y;
        }

        // Matriks A (4x4)
        $A = [
            [$n,        $sumX1,     $sumX2,     $sumX3],
            [$sumX1,    $sumX1_2,   $sumX1X2,   $sumX1X3],
            [$sumX2,    $sumX1X2,   $sumX2_2,   $sumX2X3],
            [$sumX3,    $sumX1X3,   $sumX2X3,   $sumX3_2],
        ];

        // Matriks B (4x1)
        $B = [
            [$sumY],
            [$sumX1Y],
            [$sumX2Y],
            [$sumX3Y],
        ];

        // Gunakan Gauss-Jordan eliminasi untuk menyelesaikan A · X = B
        $X = $this->gaussJordan($A, $B);

        // Return koefisien [a, b1, b2, b3]
        return $X;
    }

    private function gaussJordan($A, $B){
        $n = count($A);
        for ($i = 0; $i < $n; $i++) {
            // Gabungkan A dan B
            for ($j = 0; $j < $n; $j++) {
                $A[$j][] = $B[$j][0];
            }

            // Pivoting
            $pivot = $A[$i][$i];
            for ($k = 0; $k < $n + 1; $k++) {
                $A[$i][$k] /= $pivot;
            }

            // Eliminasi baris lain
            for ($j = 0; $j < $n; $j++) {
                if ($j != $i) {
                    $factor = $A[$j][$i];
                    for ($k = 0; $k < $n + 1; $k++) {
                        $A[$j][$k] -= $factor * $A[$i][$k];
                    }
                }
            }
        }

        // Ambil hasil akhir kolom terakhir
        $result = [];
        for ($i = 0; $i < $n; $i++) {
            $result[] = $A[$i][$n];
        }
        return $result;
    }

    public function grafik(){
        $data = SalesData::all();

        // Hitung koefisien berdasarkan semua data
        $koef = $this->calculateCoefficients();
        if (!$koef) {
            return redirect()->route('prediksi.index')->with('error', 'Data tidak cukup untuk grafik.');
        }

        [$a, $b1, $b2, $b3] = $koef;

        // Buat array label dan dua dataset: aktual & prediksi
        $labels = [];
        $dataAktual = [];
        $dataPrediksi = [];

        foreach ($data as $row) {
            $labels[] = $row->bulan;
            $dataAktual[] = $row->target_berikutnya;

            $prediksi = $a + ($b1 * $row->target_sebelumnya) + ($b2 * $row->capaian_sebelumnya) + ($b3 * $row->potongan_dp);
            $dataPrediksi[] = $prediksi;
        }

        // Hitung MAPE antara semua prediksi dan aktual
        $sumMape = 0;
        $count = 0;

        foreach ($data as $index => $row) {
            $aktual = $row->target_berikutnya;
            $prediksi = $a + ($b1 * $row->target_sebelumnya) + ($b2 * $row->capaian_sebelumnya) + ($b3 * $row->potongan_dp);

            if ($aktual != 0) {
                $sumMape += abs(($aktual - $prediksi) / $aktual);
                $count++;
            }
        }

        $mape = $count > 0 ? round(($sumMape / $count) * 100, 2) : null;

        $interpretasi = null;
        if ($mape !== null) {
            if ($mape < 10) {
                $interpretasi = 'Sangat Baik';
            } elseif ($mape < 20) {
                $interpretasi = 'Baik';
            } elseif ($mape < 50) {
                $interpretasi = 'Cukup';
            } else {
                $interpretasi = 'Buruk';
            }
        }


        return view('admin.prediction.grafik', compact('labels', 'dataAktual', 'dataPrediksi', 'mape', 'interpretasi'));
    }
}
