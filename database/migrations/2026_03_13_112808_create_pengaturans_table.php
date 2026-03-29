<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->text('lokasi')->nullable();
            $table->date('tanggal-mulai')->nullable();
            $table->date('tanggal-selesai')->nullable();
            $table->time('jam')->nullable();
            $table->text('persyaratan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};