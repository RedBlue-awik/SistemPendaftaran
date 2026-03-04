<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jalurs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jalur');
            $table->text('deskripsi')->default('Tidak ada deskripsi terkait jalur ini.');
            $table->integer('batas_pendaftaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalurs');
    }
};
