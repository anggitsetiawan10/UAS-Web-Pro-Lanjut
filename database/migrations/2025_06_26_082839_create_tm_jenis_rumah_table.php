<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tm_jenis_rumah', function (Blueprint $table) {
            $table->id('id_jenis');
            $table->string('jenis_rumah', 100);
            $table->timestamps(); // ✅ Tambah timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tm_jenis_rumah');
    }
};
