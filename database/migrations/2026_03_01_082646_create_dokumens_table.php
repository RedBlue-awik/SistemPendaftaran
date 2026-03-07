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
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pendaftaran_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('kk');
            $table->string('akta');
            $table->string('ijazah');
            $table->string('foto');

            // opsional sesuai jalur
            $table->string('ktp_orangtua');
            $table->string('kip')->default('Tidak Ada');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
