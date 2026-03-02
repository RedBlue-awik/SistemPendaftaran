@extends('layouts.app')

@section('title', 'Data Pendaftar')
@section('pageTitle', 'Data Pendaftar')

@section('content')
<div class="bg-white border border-border rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">No. Pendaftaran</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach($pendaftarans as $p)
                <tr class="table-row">
                    <td class="px-6 py-4 text-sm text-text-main font-mono">{{ $p->nomor_pendaftaran }}</td>
                    <td class="px-6 py-4 text-sm text-text-main">{{ $p->nama_lengkap ?? $p->user->name }}</td>
                    <td class="px-6 py-4">@if($p->status_pendaftaran === 'terverifikasi')<span class="badge bg-green-100 text-green-700">Terverifikasi</span>@elseif($p->status_pendaftaran === 'menunggu_verifikasi')<span class="badge bg-amber-100 text-amber-700">Menunggu</span>@else<span class="badge bg-gray-100 text-muted">Draft</span>@endif</td>
                    <td class="px-6 py-4"><a href="{{ route('admin.pendaftars.show', $p) }}" class="btn-primary px-3 py-1 rounded-lg text-xs text-white">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const pendaftars = [
        { noPendaftaran: 'SPMB-2024-001', nama: 'Muhammad Rizki', status: 'Diterima' },
        { noPendaftaran: 'SPMB-2024-002', nama: 'Putri Ayu', status: 'Proses' },
    ];
    function renderPendaftarFullTable() {
        const tbody = document.getElementById('pendaftarFullTable');
        tbody.innerHTML = pendaftars.map(p => `
            <tr class="table-row">
                <td class="px-6 py-4 text-sm text-text-main font-mono">${p.noPendaftaran}</td>
                <td class="px-6 py-4 text-sm text-text-main">${p.nama}</td>
                <td class="px-6 py-4">${getStatusBadge(p.status)}</td>
                <td class="px-6 py-4"><button class="btn-primary px-3 py-1 rounded-lg text-xs text-white">Detail</button></td>
            </tr>
        `).join('');
    }
    document.addEventListener('DOMContentLoaded', renderPendaftarFullTable);
</script>
@endpush