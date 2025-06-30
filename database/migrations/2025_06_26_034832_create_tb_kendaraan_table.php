<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kendaraan', function (Blueprint $table) {
            $table->string('kode_kendaraan', 20)->primary();
            $table->string('kode_penerima', 20);
            $table->unsignedBigInteger('id_jenis_kendaraan'); // 🔥 ini harus bigint/int

            $table->integer('jumlah')->default(0);
            $table->timestamps();

            // Foreign Key ke penerima_bantuan
            $table->foreign('kode_penerima')
                ->references('kode_penerima')
                ->on('penerima_bantuan')
                ->onDelete('cascade');

            // Foreign Key ke tm_jenis_kendaraan
            $table->foreign('id_jenis_kendaraan')
                ->references('id_jenis_kendaraan')
                ->on('tm_jenis_kendaraan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kendaraan');
    }
};
