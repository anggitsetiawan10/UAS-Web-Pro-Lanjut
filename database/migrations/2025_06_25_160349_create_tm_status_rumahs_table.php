<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tm_status_rumah', function (Blueprint $table) {
            $table->id('id_status');
            $table->string('status_rumah', 100);
            $table->timestamps(); // ✅ Tambah timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tm_status_rumah');
    }
};
