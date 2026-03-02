@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-2xl py-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-green-700 mb-4">Konfirmasi Data Pendaftaran</h2>

        <table class="w-full text-sm mb-6">
            <tbody>
                <tr class="border-b"><th class="py-2 text-left w-1/3">Nama Lengkap</th><td class="py-2">{{ $data['nama_lengkap'] }}</td></tr>
                <tr class="border-b"><th class="py-2 text-left">NIK</th><td class="py-2">{{ $data['nik'] }}</td></tr>
                <tr class="border-b"><th class="py-2 text-left">Tempat, Tanggal Lahir</th><td class="py-2">{{ $data['tempat_lahir'] }}, {{ $data['tanggal_lahir'] }}</td></tr>
                <tr class="border-b"><th class="py-2 text-left">Alamat</th><td class="py-2">{{ $data['alamat'] }}</td></tr>
                <tr class="border-b"><th class="py-2 text-left">No. HP</th><td class="py-2">{{ $data['no_hp'] }}</td></tr>
                <tr class="border-b"><th class="py-2 text-left">Asal Sekolah</th><td class="py-2">{{ $data['sekolah_asal'] }}</td></tr>
                <tr class="border-b"><th class="py-2 text-left">Jurusan Pilihan</th><td class="py-2">{{ $data['jurusan_pilihan'] }}</td></tr>
                <tr class="border-b"><th class="py-2 text-left">Gelombang</th><td class="py-2">{{ $gelombang?->nama ?? '-' }}</td></tr>
                <tr class="border-b"><th class="py-2 text-left">Jalur</th><td class="py-2">{{ $jalur?->nama ?? '-' }}</td></tr>
            </tbody>
        </table>

        <form method="POST" action="{{ route('pendaftaran.store') }}">
            @csrf
            @foreach($data as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <div class="flex justify-between">
                <a href="{{ route('pendaftaran.create') }}" class="px-4 py-2 rounded bg-gray-200">Kembali</a>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white">Kirim Pendaftaran</button>
            </div>
        </form>
    </div>
</div>
@endsection
