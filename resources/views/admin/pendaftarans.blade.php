@extends('layouts.app')

@section('title', 'Data Pendaftar')
@section('pageTitle', 'Data Pendaftar')

@section('content')
    <div class="overflow-x-auto mt-5">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

        <table id="pendaftaransTable" class="w-full bg-white display min-w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">No. Pendaftaran</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach ($pendaftarans as $p)
                    <tr class="table-row">
                        <td class="px-6 py-4 text-sm text-text-main font-mono">{{ $p->nomor_pendaftaran }}</td>
                        <td class="px-6 py-4 text-sm text-text-main">{{ $p->nama_lengkap ?? $p->user->name }}</td>
                        <td class="px-6 py-4">
                            @if ($p->status_pendaftaran === 'terverifikasi')
                                <span class="badge bg-green-100 text-green-700">Terverifikasi</span>
                            @elseif($p->status_pendaftaran === 'menunggu_verifikasi')
                            <span class="badge bg-amber-100 text-amber-700">Menunggu</span>@else<span
                                    class="badge bg-gray-100 text-muted">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4"><a href="{{ route('admin.pendaftars.show', $p) }}"
                                class="btn-primary px-3 py-1 rounded-lg text-xs text-white">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        const pendaftars = [{
                noPendaftaran: 'SPMB-2024-001',
                nama: 'Muhammad Rizki',
                status: 'Diterima'
            },
            {
                noPendaftaran: 'SPMB-2024-002',
                nama: 'Putri Ayu',
                status: 'Proses'
            },
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
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}",
                    timer: 1300
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}",
                    timer: 3000
                });
            @endif

            @if ($errors->any())
                let msgs = "";
                @foreach ($errors->all() as $err)
                    msgs += "{{ $err }} ";
                @endforeach
                Toast.fire({
                    icon: 'error',
                    title: msgs,
                    timer: 3000
                });
            @endif
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                const table = $('#pendaftaransTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    pagingType: 'simple_numbers',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'Semua']
                    ],
                    dom: '<"dt-top flex items-center justify-between"l f>rt<"dt-bottom flex items-center justify-between"i p>',
                    columnDefs: [{
                        orderable: false,
                        targets: -1
                    }],
                    language: {
                        search: "",
                        searchPlaceholder: "Cari nama, email...",
                        lengthMenu: "Tampilkan _MENU_ entri",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        },
                        info: "Menampilkan _START_–_END_ dari _TOTAL_ entri",
                        zeroRecords: "Tidak ada data",
                    }
                });
            }
        });
    </script>
@endpush
