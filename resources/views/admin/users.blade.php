@extends('layouts.app')

@section('title', 'Manajemen User')
@section('pageTitle', 'Manajemen User')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h3 class="text-xl font-bold text-text-main">Daftar User</h3>
    <button onclick="openModal('userModal')" class="btn-primary px-4 py-2.5 rounded-xl text-sm font-medium text-white flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah User
    </button>
</div>
<div class="bg-white border border-border rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-green-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">No</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach($users as $u)
                <tr class="table-row">
                    <td class="px-6 py-4 text-sm text-muted">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm text-text-main font-medium">{{ $u->name }}</td>
                    <td class="px-6 py-4 text-sm text-muted">{{ $u->email }}</td>
                    <td class="px-6 py-4">{!! $u->status_akun === 'aktif' ? '<span class="badge bg-green-100 text-green-700">Aktif</span>' : ($u->status_akun === 'ditolak' ? '<span class="badge bg-red-100 text-red-700">Ditolak</span>' : '<span class="badge bg-amber-100 text-amber-700">Menunggu</span>') !!}</td>
                    <td class="px-6 py-4">
                        <button class="py-2 pe-2 rounded-lg" onclick="openEditUser({{ $u->toJson() }})" title="Edit"><i class="fas fa-pen-to-square text-[19px]"></i></button>
                        <form method="POST" action="{{ route('admin.users.approve', $u) }}" style="display:inline">@csrf<button class="p-2 rounded-lg" title="Approve"><i class="fas fa-check-circle text-[19px]"></i></button></form>
                        <form method="POST" action="{{ route('admin.users.reject', $u) }}" style="display:inline">@csrf<button class="p-2 rounded-lg" title="Reject"><i class="fas fa-ban text-[19px]"></i></button></form>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline">@csrf @method('DELETE')<button class="py-2 ps-2 rounded-lg" title="Hapus"><i class="fas fa-trash text-[19px]"></i></button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal User (Tambah / Edit) -->
<div id="userModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeModal('userModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white border border-border rounded-2xl p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 id="userModalTitle" class="text-lg font-bold text-text-main">Tambah User Baru</h3>
            <button onclick="closeModal('userModal')" class="p-2 rounded-lg">✖</button>
        </div>
        <form id="userForm" class="space-y-4" method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <input type="hidden" name="_method" id="userFormMethod" value="POST">
            <div>
                <label class="block text-sm font-medium text-text-main mb-2">Nama Lengkap</label>
                <input id="u_name" name="name" type="text" required class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-2">Email</label>
                <input id="u_email" name="email" type="email" required class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-2">No. HP</label>
                <input id="u_phone" name="phone" type="text" class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-2">Role</label>
                <select id="u_role" name="role" class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl" required>
                    <option value="siswa">Siswa</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-2">Status Akun</label>
                <select id="u_status" name="status_akun" class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl" required>
                    <option value="menunggu">Menunggu</option>
                    <option value="aktif">Aktif</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-2">Password</label>
                <input id="u_password" name="password" type="password" class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">
                <small class="text-xs text-muted">Biarkan kosong saat edit jika tidak ingin mengubah password. Saat tambah wajib diisi.</small>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('userModal')" class="flex-1 px-4 py-2.5 bg-green-50 border border-border rounded-xl text-text-main hover:bg-green-100 transition-colors font-medium">Batal</button>
                <button type="submit" class="flex-1 btn-primary px-4 py-2.5 rounded-xl text-white font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@push('scripts')
<script>
    function openEditUser(user) {
        document.getElementById('userModalTitle').textContent = 'Edit User';
        const form = document.getElementById('userForm');
        form.action = '/admin/users/' + user.id;
        document.getElementById('userFormMethod').value = 'PUT';
        document.getElementById('u_name').value = user.name || '';
        document.getElementById('u_email').value = user.email || '';
        document.getElementById('u_phone').value = user.phone || '';
        document.getElementById('u_role').value = user.role || 'siswa';
        document.getElementById('u_status').value = user.status_akun || 'menunggu';
        document.getElementById('u_password').required = false;
        openModal('userModal');
    }

    // when opening for create
    (function(){
        const btn = document.querySelector('[onclick="openModal(\'userModal\')"]');
        if (!btn) return;
        btn.addEventListener('click', function(){
            document.getElementById('userModalTitle').textContent = 'Tambah User Baru';
            const form = document.getElementById('userForm');
            form.action = '{{ route('admin.users.store') }}';
            document.getElementById('userFormMethod').value = 'POST';
            document.getElementById('u_name').value = '';
            document.getElementById('u_email').value = '';
            document.getElementById('u_phone').value = '';
            document.getElementById('u_role').value = 'siswa';
            document.getElementById('u_status').value = 'menunggu';
            document.getElementById('u_password').required = true;
            openModal('userModal');
        });
    })();
</script>
@endpush