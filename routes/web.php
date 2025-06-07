<?php

use App\Exports\PredictionExport;
use App\Exports\SalesDataExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\SalesDataController;
use App\Models\SalesData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('landing');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'useradmin'], function(){

    Route::get('/export/prediksi', function () {
        return Excel::download(new PredictionExport, 'prediksi.xlsx');
    })->name('export.prediksi');

    Route::get('/sales/export/excel', function () {
        return Excel::download(new SalesDataExport, 'data_sales.xlsx');
    })->name('sales.export.excel');

    Route::get('/sales/export/pdf', function () {
        $data = SalesData::all();
        $pdf = Pdf::loadView('admin.sales.export_pdf', compact('data'));
        return $pdf->download('data_sales.pdf');
    })->name('sales.export.pdf');

    Route::controller(DashboardController::class)->group(function(){
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::controller(SalesDataController::class)->group(function(){
        Route::get('/sales-data', 'index')->name('sales.index');
        Route::get('sales-data/create', 'create')->name('sales.create');
        Route::post('sales-data/create', 'store')->name('sales.store');
        Route::get('/sales-data/{id}/edit', 'edit')->name('sales.edit');
        Route::put('/sales-data/{id}/edit', 'update')->name('sales.update');
        Route::delete('/sales-data/{id}', 'destroy')->name('sales.destroy');
        Route::post('/sales-data/import', 'import')->name('sales.import');
    });

    Route::controller(PredictionController::class)->group(function(){
        Route::get('/prediksi', 'index')->name('prediksi.index');
        Route::post('/prediksi/hitung', 'predict')->name('prediksi.hitung');

        Route::get('/prediksi/grafik', 'grafik')->name('prediksi.grafik');
    });
});
