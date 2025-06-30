<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tm_jenis_pekerjaan', function (Blueprint $table) {
            $table->id('id_profesi');
            $table->string('nama_profesi', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tm_jenis_pekerjaan');
    }
};
