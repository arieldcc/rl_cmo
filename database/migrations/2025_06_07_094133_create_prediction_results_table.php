<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prediction_results', function (Blueprint $table) {
            $table->id();
            $table->string('bulan', 20);
            $table->double('target_sebelumnya');
            $table->double('capaian_sebelumnya');
            $table->double('potongan_dp');
            $table->double('hasil_prediksi');
            $table->bigInteger('target_prediksi');
            $table->float('mape')->nullable(); // hasil akurasi, boleh null
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediction_results');
    }
};
