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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('gelombang_id')
                ->constrained();

            $table->foreignId('jalur_id')
                ->constrained();

            $table->string('nomor_pendaftaran')->unique();

            $table->string('nama_lengkap');
            $table->string('nik')->unique();
            $table->date('tanggal_lahir');

            $table->text('alamat');
            $table->string('no_hp');
            $table->string('sekolah_asal');
            $table->string('jurusan_pilihan');

            // STATUS PMB
            $table->enum('status_pendaftaran', [
                'draft',
                'menunggu_verifikasi',
                'terverifikasi'
            ])->default('draft');

            $table->enum('status_kelulusan', [
                'proses',
                'lulus',
                'tidak_lulus'
            ])->default('proses');

            // DAFTAR ULANG OFFLINE
            $table->enum('status_daftar_ulang', [
                'belum',
                'sudah'
            ])->default('belum');

            $table->date('tanggal_daftar_ulang')->nullable();

            $table->enum('status_akhir', [
                'calon',
                'resmi',
                'gugur'
            ])->default('calon');

            $table->timestamp('batas_daftar_ulang')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
