<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tm_jenis_kendaraan', function (Blueprint $table) {
            $table->id('id_jenis_kendaraan');
            $table->string('jenis', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tm_jenis_kendaraan');
    }
};
