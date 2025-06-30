<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_pendapatan', function (Blueprint $table) {
            $table->string('kode_pendapatan', 20)->primary();
            $table->string('kode_penerima', 20);
            $table->unsignedBigInteger('kode_profesi');
            $table->integer('pendapatan_bulanan');

            $table->foreign('kode_penerima')->references('kode_penerima')->on('penerima_bantuan')->onDelete('cascade');
            $table->foreign('kode_profesi')->references('id_profesi')->on('tm_jenis_pekerjaan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pendapatan');
    }
};
