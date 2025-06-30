<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_tanggungan', function (Blueprint $table) {
            $table->string('kode_tanggungan', 20)->primary();
            $table->string('kode_penerima', 20);
            $table->integer('jumlah_anak');
            $table->integer('anak_sekolah');
            $table->integer('anak_belum_sekolah');
            $table->integer('jumlah_tanggungan_lain');
            $table->timestamps();

            $table->foreign('kode_penerima')->references('kode_penerima')->on('penerima_bantuan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_tanggungan');
    }
};
