<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_penilaian', function (Blueprint $table) {
        $table->string('kode_penerima', 20)->primary();
        $table->double('skor_rumah')->default(0);
        $table->double('skor_kendaraan')->default(0);
        $table->double('skor_pendapatan')->default(0);
        $table->double('skor_tanggungan')->default(0);
        $table->double('skor_total')->default(0);
        $table->unsignedBigInteger('kode_status')->default(2); // 🟢 Default status Layak
        $table->text('catatan')->nullable();
        $table->date('tanggal_penilaian');
        $table->timestamps();

        $table->foreign('kode_penerima')->references('kode_penerima')->on('penerima_bantuan')->onDelete('cascade');
        $table->foreign('kode_status')->references('id_status')->on('tm_status_kelayakan')->onDelete('cascade');
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('tb_penilaian');
    }
};
