@extends('layouts.app')

@section('title', 'Manajemen Jalur')
@section('pageTitle', 'Manajemen Jalur Pendaftaran')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h3 class="text-xl font-bold text-text-main">Daftar Jalur Pendaftaran</h3>
    <button type="button" class="btn-primary px-4 py-2.5 rounded-xl text-sm font-medium text-white flex items-center gap-2" data-bs-toggle="modal" data-bs-target="#jalurModal" onclick="resetForm()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Jalur
    </button>
</div>

<!-- Grid Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($jalurs as $j)
    <div class="card-hover bg-white border border-border rounded-2xl p-6 flex flex-col">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h4 class="text-lg font-bold text-text-main">{{ $j->nama_jalur }}</h4>
                <p class="text-xs text-accent font-mono mt-1">ID: {{ $j->id }}</p>
            </div>
            <span class="badge bg-green-100 text-green-700 border border-green-200 whitespace-nowrap">
                {{ $j->pendaftarans_count ?? 0 }} Pendaftar
            </span>
        </div>
        
        <div class="flex-grow mb-4">
            <p class="text-sm text-muted mb-3">
                {{ Str::limit($j->deskripsi, 100) }}
            </p>
            <div class="flex items-center gap-2 text-sm text-muted">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Batas: <strong class="text-text-main">{{ $j->batas_pendaftaran }}</strong> orang</span>
            </div>
        </div>

        <div class="flex gap-2 border-t border-border pt-4 mt-auto">
            <button onclick="editJalur({{ $j->id }})" class="flex-1 px-3 py-2 bg-green-50 border border-border rounded-lg text-sm text-text-main hover:bg-green-100 transition font-medium flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </button>
            <form method="POST" action="{{ route('admin.jalurs.destroy', $j) }}" onsubmit="return confirm('Yakin ingin menghapus jalur ini?')" class="flex-1">
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

<div class="modal fade" id="jalurModal" tabindex="-1" aria-labelledby="jalurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-border rounded-2xl shadow-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-b border-border bg-green-50 p-4">
                <h5 class="modal-title font-bold text-text-main text-lg" id="jalurModalLabel">Tambah Jalur Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body p-6">
                <form id="jalurForm" method="POST" action="{{ route('admin.jalurs.store') }}">
                    @csrf
                    <!-- Hidden input untuk method spoofing (PUT saat edit) -->
                    <input type="hidden" name="_method" id="jalurFormMethod" value="POST">
                    
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-text-main mb-2">Nama Jalur</label>
                        <input type="text" id="j_nama" name="nama_jalur" required class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent transition text-text-main" placeholder="Contoh: Jalur Prestasi Akademik">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-text-main mb-2">Deskripsi</label>
                        <textarea id="j_deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent transition text-text-main resize-none" placeholder="Jelaskan singkat tentang jalur ini..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-text-main mb-2">Batas Pendaftaran</label>
                        <input type="number" id="j_batas" name="batas_pendaftaran" required class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent transition text-text-main" placeholder="Jumlah maksimal pendaftar">
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
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

<script>
    // Simpan data jalur ke variabel JS untuk akses cepat
    const jalurData = @json($jalurs);
    let jalurModal;

    // Inisialisasi Modal Bootstrap saat dokumen siap
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('jalurModal');
        jalurModal = new bootstrap.Modal(modalElement);
    });

    // Fungsi untuk mereset form (digunakan saat tombol "Tambah" diklik)
    function resetForm() {
        document.getElementById('jalurForm').reset();
        document.getElementById('jalurForm').action = "{{ route('admin.jalurs.store') }}";
        document.getElementById('jalurFormMethod').value = 'POST';
        document.getElementById('jalurModalLabel').textContent = 'Tambah Jalur Baru';
    }

    // Fungsi untuk mengisi form saat tombol "Edit" diklik
    function editJalur(id) {
        const jalur = jalurData.find(j => j.id === id);
        if(!jalur) return;

        // Update Judul Modal
        document.getElementById('jalurModalLabel').textContent = 'Edit Jalur';

        // Update Form Action & Method
        document.getElementById('jalurForm').action = "/admin/jalurs/" + id;
        document.getElementById('jalurFormMethod').value = 'PUT';

        // Isi Data
        document.getElementById('j_nama').value = jalur.nama_jalur || '';
        document.getElementById('j_deskripsi').value = jalur.deskripsi || '';
        document.getElementById('j_batas').value = jalur.batas_pendaftaran || '';

        // Tampilkan Modal Bootstrap
        jalurModal.show();
    }

    // Fungsi untuk submit form
    function submitForm() {
        document.getElementById('jalurForm').submit();
    }
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
        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}", timer: 1300 });
        @endif

        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}", timer: 3000 });
        @endif

        @if ($errors->any())
            let msgs = "";
            @foreach ($errors->all() as $err)
                msgs += "{{ $err }} ";
            @endforeach
            Toast.fire({ icon: 'error', title: msgs, timer: 3000 });
        @endif
    });
</script>
@endpush