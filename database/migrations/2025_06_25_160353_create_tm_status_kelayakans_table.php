<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tm_status_kelayakan', function (Blueprint $table) {
            $table->id('id_status');
            $table->string('nama_status', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tm_status_kelayakan');
    }
};
