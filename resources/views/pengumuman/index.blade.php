@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold">Pengumuman Hasil Seleksi</h2>
    @if(!$pendaftaran)
        <p>Anda belum melakukan pendaftaran.</p>
    @else
        <p><strong>Status Seleksi:</strong> {{ $pendaftaran->status_kelulusan }}</p>
        @if($pendaftaran->status_kelulusan === 'lulus')
            <p>Selamat, Anda dinyatakan lulus.</p>
            <p>Batas daftar ulang: {{ $pendaftaran->batas_daftar_ulang ?? 'Belum ditentukan' }}</p>
        @elseif($pendaftaran->status_kelulusan === 'tidak_lulus')
            <p>Mohon maaf, Anda tidak lulus.</p>
        @else
            <p>Proses seleksi masih berjalan.</p>
        @endif
    @endif
</div>
@endsection
