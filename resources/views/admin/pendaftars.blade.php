@extends('layouts.app')

@section('title', 'Data Pendaftar')
@section('pageTitle', 'Data Pendaftar')

@section('content')
    <div class="flex justify-end mb-4 mt-2">
        <div class="flex items-center gap-2">
            <button class="bg-blue-700 text-white px-4 py-[12px] rounded-[5px] text-sm font-medium text-white" data-bs-toggle="modal"
                data-bs-target="#filterModal"><i class="fas fa-filter me-2"></i>Filter</button>

            <button data-bs-toggle="modal" data-bs-target="#pengaturan"
                class="bg-green-700 px-4 py-[12px] rounded-[5px] text-sm font-medium text-white">
                <i class="bi bi-gear-fill me-2"></i>Pengaturan Daftar Ulang
            </button>
        </div>
    </div>

    <div class="overflow-x-auto mt-5">
        <table id="pendaftaransTable" class="w-full bg-white display">
            <thead class="bg-green-500">
                <tr>
                    <th class="px-2 py-4 text-center text-xs font-semibold text-muted uppercase">No.P</th>
                    <th class="px-3 py-4 text-center text-xs font-semibold text-muted uppercase">Nama</th>
                    <th class="px-3 py-4 text-center text-xs font-semibold text-muted uppercase">Gelombang</th>
                    <th class="px-3 py-4 text-center text-xs font-semibold text-muted uppercase">Jalur</th>
                    <th class="px-3 py-4 text-center text-xs font-semibold text-muted uppercase">Status</th>
                    <th class="px-3 py-4 text-center text-xs font-semibold text-muted uppercase">Status_Akhir</th>
                    <th class="px-3 py-4 text-center text-xs font-semibold text-muted uppercase">Daftar Ulang</th>
                    <th class="px-3 py-4 text-center text-xs font-semibold text-muted uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach ($pendaftarans as $p)
                    <tr class="table-row text-center">
                        <td class="px-2 py-4 text-sm text-text-main font-mono">{{ $p->nomor_pendaftaran }}</td>
                        <td class="px-3 py-4 text-sm text-text-main">{{ $p->nama_lengkap ?? $p->user->name }}</td>
                        <td class="px-3 py-4 text-sm text-text-main">{{ $p->gelombang->nama }}</td>
                        <td class="px-3 py-4 text-sm text-text-main">{{ $p->jalur->nama_jalur }}</td>
                        <td class="px-3 py-4">
                            @if ($p->status_kelulusan === 'lulus')
                                <span class="badge bg-green-100 text-green-700">Lulus</span>
                            @elseif($p->status_kelulusan === 'tidak_lulus')
                                <span class="badge bg-red-100 text-red-700">Tidak Lulus</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-700">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-3 py-4">
                            @if ($p->status_akhir === 'resmi')
                                <span class="badge bg-green-100 text-green-700">Resmi</span>
                            @elseif($p->status_akhir === 'gugur')
                                <span class="badge bg-red-100 text-red-700">Gugur</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-700">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-3 py-4">
                            @if ($p->status_daftar_ulang === 'sudah')
                                <span class="badge bg-green-100 text-green-700">Sudah</span>
                            @elseif($p->status_daftar_ulang === 'belum')
                                <span class="badge bg-gray-200 text-muted">Belum</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 ">
                            <a href="" data-bs-toggle="modal" data-bs-target="#details{{ $p->id }}"
                                class="rounded-lg p-2" title="Detail-{{ $p->nama_lengkap }}">
                                <i class="fas fa-user-gear text-[19px]"></i>
                            </a>
                            <a href="" data-bs-toggle="modal" data-bs-target="#statusdaftar{{ $p->id }}"
                                class="rounded-lg p-2" title="Status Daftar Ulang-{{ $p->nama_lengkap }}">
                                <i class="fa-solid fa-file-circle-check text-[19px]"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-green-50 border-b border-gray-200">
                    <h5 class="modal-title font-bold text-text-main">Filter Pendaftar</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET" action="{{ route('admin.pendaftars.index') }}">
                    <div class="modal-body space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Gelombang</label>
                            <select name="gelombang" class="w-full px-3 py-2.5 bg-green-50 border border-border rounded-xl">
                                <option value="">-- Semua Gelombang --</option>
                                @isset($gelombangs)
                                    @foreach ($gelombangs as $g)
                                        <option value="{{ $g->id }}"
                                            {{ request('gelombang') == $g->id ? 'selected' : '' }}>{{ $g->nama }}
                                            ({{ $g->tanggal_mulai }})</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Status Siswa</label>
                            <select name="status_siswa" class="w-full px-3 py-2.5 bg-green-50 border border-border rounded-xl">
                                <option value="">-- Semua --</option>
                                <option value="resmi" {{ request('status_siswa') == 'resmi' ? 'selected' : '' }}>Resmi
                                </option>
                                <option value="belum" {{ request('status_siswa') == 'belum' ? 'selected' : '' }}>Belum
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Status Daftar Ulang</label>
                            <select name="daftar_ulang" class="w-full px-3 py-2.5 bg-green-50 border border-border rounded-xl">
                                <option value="">-- Semua --</option>
                                <option value="belum" {{ request('daftar_ulang') == 'belum' ? 'selected' : '' }}>Belum
                                </option>
                                <option value="sudah" {{ request('daftar_ulang') == 'sudah' ? 'selected' : '' }}>Sudah
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-t border-gray-200">
                        <button type="button" class="px-4 py-2.5 bg-green-50 border border-border rounded-xl text-text-main hover:bg-green-100 transition-colors font-medium" id="resetFilter">Reset</button>
                        <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @foreach ($pendaftarans as $p)
        <div class="modal fade" id="details{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header bg-green-50 border-b border-gray-200">
                        <h5 class="modal-title font-bold text-text-main">
                            Detail Pendaftaran
                        </h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body space-y-6">

                        {{-- DATA PENDAFTAR --}}
                        <div class="grid grid-cols-2 gap-4 text-sm mb-4">

                            <div>
                                <span class="text-gray-500">Nama</span>
                                <p class="font-semibold">{{ $p->nama_lengkap }}</p>
                            </div>

                            <div>
                                <span class="text-gray-500">Email</span>
                                <p class="font-semibold">{{ $p->user->email }}</p>
                            </div>

                            <div>
                                <span class="text-gray-500">No Telepon</span>
                                <p class="font-semibold">{{ $p->user->phone }}</p>
                            </div>

                            <div>
                                <span class="text-gray-500">Gelombang</span>
                                <p class="font-semibold">{{ $p->gelombang->nama }}</p>
                            </div>

                            <div>
                                <span class="text-gray-500">Jalur</span>
                                <p class="font-semibold">{{ $p->jalur->nama_jalur }}</p>
                            </div>

                            <div class="flex flex-col gap-3">
                                <div>
                                    <span class="text-gray-500">Status Kelulusan :</span>

                                    @if ($p->status_kelulusan == 'lulus')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-lg">
                                            Lulus
                                        </span>
                                    @elseif($p->status_kelulusan == 'tidak_lulus')
                                        <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-lg">
                                            Tidak Lulus
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-lg">
                                            Proses
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-gray-500">Status Daftar Ulang :</span>

                                    @if ($p->status_daftar_ulang == 'sudah')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-lg">
                                            Sudah
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-lg">
                                            Belum
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>


                        {{-- DOKUMEN --}}
                        <div>
                            <h3 class="font-bold text-text-main mb-3">
                                Dokumen
                            </h3>

                            @if ($p->dokumen)
                                <div class="grid grid-cols-2 gap-3 text-sm">

                                    <a href="{{ asset('storage/' . $p->dokumen->kk) }}" target="_blank"
                                        class="bg-green-50 border border-border rounded-xl p-3 hover:bg-green-100">
                                        <i class="bi bi-file-earmark-pdf-fill text-red-500 me-1"></i> Kartu Keluarga
                                    </a>

                                    <a href="{{ asset('storage/' . $p->dokumen->ktp_orangtua) }}" target="_blank"
                                        class="bg-green-50 border border-border rounded-xl p-3 hover:bg-green-100">
                                        <i class="bi bi-person-vcard-fill text-orange-500 me-1"></i> KTP Orang Tua
                                    </a>

                                    <a href="{{ asset('storage/' . $p->dokumen->akta) }}" target="_blank"
                                        class="bg-green-50 border border-border rounded-xl p-3 hover:bg-green-100">
                                        <i class="bi bi-file-text-fill text-gray-700 me-1"></i> Akta Kelahiran
                                    </a>

                                    <a href="{{ asset('storage/' . $p->dokumen->ijazah) }}" target="_blank"
                                        class="bg-green-50 border border-border rounded-xl p-3 hover:bg-green-100">
                                        <i class="bi bi-mortarboard-fill text-blue-500 me-1"></i> Ijazah
                                    </a>

                                    <a href="{{ asset('storage/' . $p->dokumen->foto) }}" target="_blank"
                                        class="bg-green-50 border border-border rounded-xl p-3 hover:bg-green-100">
                                        <i class="bi bi-person-bounding-box text-purple-500 me-1"></i> Foto
                                    </a>

                                    @if ($p->dokumen->kip)
                                        <a href="{{ asset('storage/' . $p->dokumen->kip) }}" target="_blank"
                                            class="bg-green-50 border border-border rounded-xl p-3 hover:bg-green-100">
                                            <i class="bi bi-award-fill text-yellow-500 me-1"></i> KIP
                                        </a>
                                    @else
                                        <span
                                            class="bg-gray-50 border border-border rounded-xl p-3 text-gray-700 cursor-not-allowed">
                                            <i class="bi bi-award-fill text-gray-500 me-1"></i> Tidak ada KIP yang di
                                            unggah
                                        </span>
                                    @endif

                                </div>
                            @else
                                <p class="text-gray-500 text-sm">
                                    Belum mengupload dokumen.
                                </p>
                            @endif
                        </div>


                        {{-- UPDATE SELEKSI --}}
                        <div>
                            <h3 class="font-bold text-text-main mb-3">
                                Update Seleksi
                            </h3>

                            <form id="selectForm{{ $p->id }}" method="POST" action="{{ route('admin.pendaftars.select', $p) }}" class="flex gap-2">
                                @csrf

                                <select name="status_kelulusan"
                                    class="px-4 py-2.5 bg-green-50 border border-border rounded-xl">

                                    <option value="menunggu">Menunggu</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="tidak_lulus">Tidak Lulus</option>

                                </select>

                            </form>

                        </div>

                        <div class="modal-footer bg-white border-t border-gray-200">
                            <button
                                class="px-4 py-2.5 bg-green-50 border border-border rounded-xl text-text-main hover:bg-green-100"
                                data-bs-dismiss="modal">
                                Tutup
                            </button>

                            <button type="submit" form="selectForm{{ $p->id }}"
                                class="px-4 py-2.5 bg-green-700 rounded-xl text-white font-medium hover:bg-green-800">
                                Simpan
                            </button>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    @foreach ($pendaftarans as $p)
        <div class="modal fade" id="statusdaftar{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-green-50 border-b border-gray-200">
                        <h5 class="modal-title font-bold text-text-main">Ubah Status Daftar Ulang - {{ $p->nama_lengkap }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.pendaftars.confirm_daftar_ulang', $p) }}">
                        @csrf
                        <div class="modal-body">
                            <label class="block text-sm font-medium mb-2">Status Daftar Ulang</label>
                            <select name="status" class="w-full px-3 py-2.5 bg-green-50 border border-border rounded-xl">
                                <option value="belum" {{ $p->status_daftar_ulang == 'belum' ? 'selected' : '' }}>Belum
                                </option>
                                <option value="sudah" {{ $p->status_daftar_ulang == 'sudah' ? 'selected' : '' }}>Sudah
                                </option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <div class="modal-footer bg-white border-t border-gray-200">
                            <button type="button"
                                class="px-4 py-2.5 bg-green-50 border border-border rounded-xl text-text-main hover:bg-green-100 transition-colors font-medium"
                                data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-4 py-2.5 bg-green-700 rounded-xl text-white font-medium hover:bg-green-800 transition-colors">
                                Simpan
                            </button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    @foreach ($pengaturan as $pt)
        <div class="modal fade" id="pengaturan" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header bg-green-50 border-b border-gray-200">
                        <h5 class="modal-title font-bold text-text-main">
                            Pengaturan Daftar Ulang
                        </h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                        @csrf

                        <div class="modal-body space-y-4">

                            {{-- Lokasi --}}
                            <div>
                                <label class="block text-sm font-medium text-text-main mb-2">
                                    Lokasi
                                </label>

                                <textarea name="lokasi" rows="2"
                                    class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">{{ old('lokasi', $pt->lokasi ?? '') }}</textarea>
                            </div>

                            {{-- Tanggal --}}
                            <div class="grid grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm font-medium text-text-main mb-2">
                                        Tanggal Mulai
                                    </label>

                                    <input type="text" name="tanggal_mulai"
                                        class="tanggalPicker w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent"
                                        value="{{ old('tanggal_mulai', $pt->tanggal_mulai ?? '') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-text-main mb-2">
                                        Tanggal Selesai
                                    </label>

                                    <input type="text" name="tanggal_selesai"
                                        class="tanggalPicker w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent"
                                        value="{{ old('tanggal_selesai', $pt->tanggal_selesai ?? '') }}">
                                </div>

                            </div>

                            {{-- Jam --}}
                            <div>
                                <label class="block text-sm font-medium text-text-main mb-2">
                                    Jam Pelaksanaan
                                </label>

                                <input type="time" name="jam"
                                    class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent"
                                    value="{{ old('jam', $pt->jam ?? '') }}">
                            </div>

                            {{-- Persyaratan --}}
                            <div>
                                <label class="block text-sm font-medium text-text-main mb-2">
                                    Persyaratan
                                </label>

                                <textarea name="persyaratan" rows="3"
                                    class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">{{ old('persyaratan', $pt->persyaratan ?? '') }}</textarea>
                            </div>

                        </div>

                        <div class="modal-footer bg-white border-t border-gray-200">
                            <button type="button"
                                class="px-4 py-2.5 bg-green-50 border border-border rounded-xl text-text-main hover:bg-green-100 transition-colors font-medium"
                                data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-4 py-2.5 bg-green-700 rounded-xl text-white font-medium hover:bg-green-800 transition-colors">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endforeach


@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr(".tanggalPicker", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d F Y",
        });
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

            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                const table = $('#pendaftaransTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    pagingType: 'simple_numbers',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'Semua']
                    ],
                    dom: '<"dt-top flex items-center text-green-950 text-nowarp justify-between mb-3"l f>rt<"dt-bottom text-green-950 flex items-center text-nowarp justify-between mt-2"i p>',
                    columnDefs: [{
                        orderable: false,
                        targets: -1
                    }, {
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0
                    }],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    },
                    language: {
                        search: "",
                        searchPlaceholder: "Cari pendaftar...",
                        lengthMenu: "Tampilkan _MENU_ Data",
                        paginate: {
                            previous: "<",
                            next: ">"
                        },
                        info: "Menampilkan _START_–_END_ dari _TOTAL_ Data",
                        zeroRecords: "Tidak ada data",
                    }
                });

                // Reset filter button
                document.getElementById('resetFilter')?.addEventListener('click', function() {
                    const form = this.closest('.modal-content').querySelector('form');
                    if (form) {
                        form.querySelectorAll('select').forEach(s => s.value = '');
                        form.submit();
                    }
                });
            }
        });
    </script>
@endpush
