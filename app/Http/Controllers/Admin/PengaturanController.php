<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam' => 'required',
            'persyaratan' => 'required|string',
        ]);

        Pengaturan::updateOrCreate(
            ['id' => 1],
            $request->only(['lokasi', 'tanggal_mulai', 'tanggal_selesai', 'jam', 'persyaratan'])
        );

        return back()->with('success', 'Pengaturan daftar ulang berhasil diperbarui.');
    }
}