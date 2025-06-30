<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penerima_bantuan', function (Blueprint $table) {
            $table->string('kode_penerima', 20)->primary();
            $table->string('nik', 20)->unique();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('kontak', 20);
            $table->date('tanggal_survei');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima_bantuan');
    }
};
