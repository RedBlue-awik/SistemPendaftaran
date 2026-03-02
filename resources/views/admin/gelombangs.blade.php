@extends('layouts.app')

@section('title', 'Manajemen Gelombang')
@section('pageTitle', 'Manajemen Gelombang')

@section('content')
<!-- Tambahkan Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Notifikasi Sukses -->
@if(session('success'))
<div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl flex items-center justify-between animate-fade-in-up shadow-sm">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
</div>
@endif

<div class="flex items-center justify-between mb-6">
    <h3 class="text-xl font-bold text-text-main">Daftar Gelombang Pendaftaran</h3>
    <button type="button" class="btn-primary px-4 py-2.5 rounded-xl text-sm font-medium text-white flex items-center gap-2" data-bs-toggle="modal" data-bs-target="#gelombangModal" onclick="resetForm()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Gelombang
    </button>
</div>

<!-- Grid Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($gelombangs as $g)
    <div class="card-hover bg-white border border-border rounded-2xl p-6 flex flex-col">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h4 class="text-lg font-bold text-text-main">{{ $g->nama }}</h4>
                <p class="text-xs text-muted mt-1">
                    {{ \Carbon\Carbon::parse($g->tanggal_mulai)->translatedFormat('d M Y') }} - 
                    {{ \Carbon\Carbon::parse($g->tanggal_selesai)->translatedFormat('d M Y') }}
                </p>
            </div>
            @if($g->status === 'aktif')
                <span class="badge bg-green-100 text-green-700 border border-green-200">Aktif</span>
            @else
                <span class="badge bg-gray-100 text-gray-600 border border-gray-200">Tutup</span>
            @endif
        </div>
        
        <div class="flex-grow mb-4">
            <div class="flex justify-between items-center text-sm mb-2">
                <span class="text-muted">Batas Pendaftar</span>
                <span class="font-bold text-text-main">{{ $g->batas_pendaftaran }} Orang</span>
            </div>
        </div>

        <div class="flex gap-2 border-t border-border pt-4 mt-auto">
            <button onclick="editGelombang({{ $g->id }})" class="flex-1 px-3 py-2 bg-green-50 border border-border rounded-lg text-sm text-text-main hover:bg-green-100 transition font-medium flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </button>
            <form method="POST" action="{{ route('admin.gelombangs.destroy', $g) }}" onsubmit="return confirm('Yakin ingin menghapus?')" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-3 py-2 bg-red-50 border border-red-100 rounded-lg text-sm text-red-600 hover:bg-red-100 transition font-medium flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="gelombangModal" tabindex="-1" aria-labelledby="gelombangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-border rounded-2xl shadow-2xl overflow-hidden">
            <div class="modal-header border-b border-border bg-green-50 p-4">
                <h5 class="modal-title font-bold text-text-main text-lg" id="gelombangModalLabel">Tambah Gelombang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-6">
                <form id="gelForm" method="POST" action="{{ route('admin.gelombangs.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="gelFormMethod" value="POST">
                    
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-text-main mb-2">Nama Gelombang</label>
                        <input type="text" id="g_nama" name="nama" required class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent transition text-text-main" placeholder="Contoh: Gelombang 1">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Tanggal Mulai</label>
                            <input type="text" id="g_mulai" name="tanggal_mulai" required class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent transition text-text-main cursor-pointer" placeholder="Pilih Tanggal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Tanggal Selesai</label>
                            <input type="text" id="g_selesai" name="tanggal_selesai" required class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent transition text-text-main cursor-pointer" placeholder="Pilih Tanggal">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Batas Pendaftar</label>
                            <input type="number" id="g_batas" name="batas_pendaftaran" required class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent transition text-text-main" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Status</label>
                            <select id="g_status" name="status" class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent transition text-text-main">
                                <option value="aktif">Aktif</option>
                                <option value="tutup">Tutup</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-t border-border bg-white p-4">
                <button type="button" class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl text-text-main hover:bg-gray-200 transition font-medium" data-bs-dismiss="modal">Batal</button>
                <button type="button" onclick="submitForm()" class="btn-primary px-6 py-2 rounded-xl text-white font-medium transition duration-300">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Flatpickr JS & Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>


<script>
    // Simpan data gelombang ke variabel JS
    const gelombangData = @json($gelombangs);
    let gelombangModal;
    let fpMulai, fpSelesai; // Variabel untuk instance Flatpickr

    // Inisialisasi Modal & Flatpickr saat dokumen siap
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('gelombangModal');
        gelombangModal = new bootstrap.Modal(modalElement);

        // Konfigurasi Flatpickr
        const commonConfig = {
            dateFormat: "Y-m-d", 
            altInput: true,      
            altFormat: "j F Y",  
            locale: "id",        
            disableMobile: "false",
        };

        // Inisialisasi Flatpickr pada input tanggal
        fpMulai = flatpickr("#g_mulai", commonConfig);
        fpSelesai = flatpickr("#g_selesai", commonConfig);
    });

    // Fungsi untuk mereset form
    function resetForm() {
        document.getElementById('gelForm').reset();
        document.getElementById('gelForm').action = "{{ route('admin.gelombangs.store') }}";
        document.getElementById('gelFormMethod').value = 'POST';
        document.getElementById('gelombangModalLabel').textContent = 'Tambah Gelombang Baru';
        
        // Reset Flatpickr
        if(fpMulai) fpMulai.clear();
        if(fpSelesai) fpSelesai.clear();
    }

    // Fungsi untuk mengisi form saat Edit
    function editGelombang(id) {
        const gelombang = gelombangData.find(g => g.id === id);
        if(!gelombang) return;

        // Update Judul Modal
        document.getElementById('gelombangModalLabel').textContent = 'Edit Gelombang';

        // Update Form Action & Method
        document.getElementById('gelForm').action = "/admin/gelombangs/" + id;
        document.getElementById('gelFormMethod').value = 'PUT';

        // Isi Data
        document.getElementById('g_nama').value = gelombang.nama;
        
        // Isi Data ke Flatpickr menggunakan setDate
        if(fpMulai) fpMulai.setDate(gelombang.tanggal_mulai);
        if(fpSelesai) fpSelesai.setDate(gelombang.tanggal_selesai);
        
        document.getElementById('g_batas').value = gelombang.batas_pendaftaran;
        document.getElementById('g_status').value = gelombang.status;

        // Tampilkan Modal
        gelombangModal.show();
    }

    // Fungsi untuk submit form
    function submitForm() {
        document.getElementById('gelForm').submit();
    }
</script>
@endpush