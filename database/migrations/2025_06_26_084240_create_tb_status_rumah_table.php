<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_status_rumah', function (Blueprint $table) {
            $table->string('kode_kepemilikan_rumah', 20)->primary();
            $table->string('kode_penerima', 20);
            $table->unsignedBigInteger('id_status_rumah');
            $table->integer('luas_rumah');
            $table->unsignedBigInteger('id_kondisi');
            $table->unsignedBigInteger('id_jenis');
            $table->integer('jumlah_penghuni')->nullable();
            $table->timestamps(); // ✅ Tambah timestamps

            // 🔗 Foreign Key
            $table->foreign('kode_penerima')
                ->references('kode_penerima')
                ->on('penerima_bantuan')
                ->onDelete('cascade');

            $table->foreign('id_status_rumah')
                ->references('id_status')
                ->on('tm_status_rumah')
                ->onDelete('cascade');

            $table->foreign('id_kondisi')
                ->references('id_kondisi')
                ->on('tm_kondisi_rumah')
                ->onDelete('cascade');

            $table->foreign('id_jenis')
                ->references('id_jenis')
                ->on('tm_jenis_rumah')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_status_rumah');
    }
};
