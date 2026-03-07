@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold">Detail Pendaftaran</h2>
    <div class="mt-4">
        <p><strong>Nama:</strong> {{ $pendaftaran->nama_lengkap }}</p>
        <p><strong>Email:</strong> {{ $pendaftaran->user->email }}</p>
        <p><strong>No Telpone:</strong> {{ $pendaftaran->user->phone }}</p>
        <p><strong>Gelombang:</strong> {{ $pendaftaran->gelombang->nama }}</p>
        <p><strong>Jalur:</strong> {{ $pendaftaran->jalur->nama_jalur }}</p>
        <p><strong>Status Pendaftaran:</strong> {{ $pendaftaran->status_pendaftaran }}</p>
        <p><strong>Status Kelulusan:</strong> {{ $pendaftaran->status_kelulusan }}</p>
    </div>

    <div class="mt-4">
        <h3 class="font-bold">Dokumen</h3>
        @if($pendaftaran->dokumen)
            <ul>
                <li>KK: <a href="{{ asset('storage/'.$pendaftaran->dokumen->kk) }}" target="_blank">Lihat</a></li>
                <li>Akta: <a href="{{ asset('storage/'.$pendaftaran->dokumen->akta) }}" target="_blank">Lihat</a></li>
                <li>Ijazah: <a href="{{ asset('storage/'.$pendaftaran->dokumen->ijazah) }}" target="_blank">Lihat</a></li>
                <li>Foto: <a href="{{ asset('storage/'.$pendaftaran->dokumen->foto) }}" target="_blank">Lihat</a></li>
            </ul>
        @else
            <p>Belum mengupload dokumen.</p>
        @endif
    </div>

    <div class="mt-4">
        <form method="POST" action="{{ route('admin.pendaftars.verify', $pendaftaran) }}" style="display:inline">
            @csrf
            <button class="bg-green-600 text-white px-3 py-1 rounded">Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('admin.pendaftars.select', $pendaftaran) }}" class="inline-block ml-2">
            @csrf
            <select name="status_kelulusan" class="border p-2">
                <option value="proses">proses</option>
                <option value="lulus">lulus</option>
                <option value="tidak_lulus">tidak_lulus</option>
            </select>
            <input type="date" name="batas_daftar_ulang" class="border p-2" />
            <button class="bg-blue-600 text-white px-3 py-1 rounded">Update Seleksi</button>
        </form>
        @if($pendaftaran->status_kelulusan === 'lulus' && $pendaftaran->status_daftar_ulang === 'belum')
            <form method="POST" action="{{ route('admin.pendaftars.confirm_daftar_ulang', $pendaftaran) }}" class="inline-block ml-2">
                @csrf
                <button class="bg-indigo-600 text-white px-3 py-1 rounded">Konfirmasi Daftar Ulang</button>
            </form>
        @endif
    </div>
</div>
@endsection
