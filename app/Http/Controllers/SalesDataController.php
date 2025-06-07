<?php

namespace App\Http\Controllers;

use App\Models\SalesData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalesDataController extends Controller
{
    public function index(){
        $data = SalesData::latest()->paginate(10);
        return view('admin.sales.index', compact('data'));
    }

    public function create(){
        return view('admin.sales.create');
    }

    public function store(Request $request){
        $request->validate([
            'bulan' => 'required|string|max:20',
            'target_sebelumnya' => 'required|numeric',
            'capaian_sebelumnya' => 'required|numeric',
            'potongan_dp' => 'required|numeric',
            'target_berikutnya' => 'required|numeric',
        ]);

        $sales = new SalesData();
        $sales->bulan = $request->bulan;
        $sales->target_sebelumnya = $request->target_sebelumnya;
        $sales->capaian_sebelumnya = $request->capaian_sebelumnya;
        $sales->potongan_dp = $request->potongan_dp;
        $sales->target_berikutnya = $request->target_berikutnya;
        $sales->save();

        return redirect()->route('sales.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        $data = Excel::toArray([], $request->file('file'))[0];

        foreach ($data as $index => $row) {
            if ($index === 0) continue; // skip header
            $sales = new SalesData();
            $sales->bulan = $row[0];
            $sales->target_sebelumnya = $row[1];
            $sales->capaian_sebelumnya = $row[2];
            $sales->potongan_dp = $row[3];
            $sales->target_berikutnya = $row[4];
            $sales->save();
        }

        return redirect()->route('sales.index')->with('success', 'Data berhasil diimpor.');
    }

    public function edit($id){
        $data = SalesData::findOrFail($id);
        return view('admin.sales.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'bulan' => 'required|string|max:20',
            'target_sebelumnya' => 'required|numeric',
            'capaian_sebelumnya' => 'required|numeric',
            'potongan_dp' => 'required|numeric',
            'target_berikutnya' => 'required|numeric',
        ]);

        $sales = SalesData::findOrFail($id);
        $sales->bulan = $request->bulan;
        $sales->target_sebelumnya = $request->target_sebelumnya;
        $sales->capaian_sebelumnya = $request->capaian_sebelumnya;
        $sales->potongan_dp = $request->potongan_dp;
        $sales->target_berikutnya = $request->target_berikutnya;
        $sales->save();

        return redirect()->route('sales.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id){
        SalesData::destroy($id);
        return redirect()->route('sales.index')->with('success', 'Data berhasil dihapus.');
    }
}
