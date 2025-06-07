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
        Schema::create('sales_data', function (Blueprint $table) {
            $table->id();
            $table->string('bulan', 20);
            $table->bigInteger('target_sebelumnya');
            $table->bigInteger('capaian_sebelumnya');
            $table->bigInteger('potongan_dp');
            $table->bigInteger('target_berikutnya');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_data');
    }
};
