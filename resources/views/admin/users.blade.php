@extends('layouts.app')

@section('title', 'Manajemen User')
@section('pageTitle', 'Manajemen User')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-text-main">Daftar User</h3>
        <button type="button" id="btnAddUser"
            class="bg-green-700 px-4 py-[12px] rounded-[5px] text-sm font-medium text-white flex items-center gap-2">
            <i class="fas fa-plus text-[15px] me-0.5"></i>
            Tambah User
        </button>
    </div>
    <div class="overflow-x-auto mt-5">
        <table id="usersTable" class="w-full bg-white display">
            <thead class="bg-green-500">
                <tr>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase">No</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase">Nama</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase">Email</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach ($users as $u)
                    <tr class="table-row text-center">
                        <td class="px-6 py-4 text-sm text-muted">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-text-main font-medium">{{ $u->name }}</td>
                        <td class="px-6 py-4 text-sm text-muted">{{ $u->email }}</td>
                        <td class="px-6 py-4">{!! $u->status_akun === 'nonaktif'
                            ? '<span class="badge bg-red-100 text-red-700">Non-Aktif</span>'
                            : '<span class="badge bg-green-100 text-green-700">Aktif</span>' !!}</td>
                        <td class="px-6 py-4">
                            <button class="py-2 pe-2 rounded-lg" onclick="openEditUser({{ $u->toJson() }})"
                                title="Edit"><i class="fas fa-pen-to-square text-[19px]"></i></button>
                            <form method="POST" action="{{ route('admin.users.approve', $u) }}" style="display:inline">
                                @csrf<button class="p-2 rounded-lg" title="Approve"><i
                                        class="fas fa-check-circle text-[19px]"></i></button></form>
                            <form method="POST" action="{{ route('admin.users.reject', $u) }}" style="display:inline">
                                @csrf<button class="p-2 rounded-lg" title="Reject"><i
                                        class="fas fa-ban text-[19px]"></i></button></form>
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline">
                                @csrf @method('DELETE')<button onclick="confirmDelete(event, this)"
                                    class="py-2 ps-2 rounded-lg" title="Hapus"><i
                                        class="fas fa-trash text-[19px]"></i></button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Bootstrap 5 -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-green-50 border-b border-gray-200">
                    <h5 class="modal-title font-bold text-text-main" id="userModalLabel">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="userFormMethod" value="POST">
                    <div class="modal-body space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Nama Lengkap</label>
                            <input id="u_name" name="name" type="text" required
                                class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Email</label>
                            <input id="u_email" name="email" type="email" required
                                class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">No. HP</label>
                            <input id="u_phone" name="phone" type="text"
                                class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Role</label>
                            <select id="u_role" name="role"
                                class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl" required>
                                <option value="siswa">Siswa</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Status Akun</label>
                            <select id="u_status" name="status_akun"
                                class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl" required>
                                <option value="menunggu">Menunggu</option>
                                <option value="aktif">Aktif</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-main mb-2">Password</label>
                            <input id="u_password" name="password" type="password"
                                class="w-full px-4 py-2.5 bg-green-50 border border-border rounded-xl focus:outline-none focus:border-accent">
                            <small class="text-xs text-muted">Biarkan kosong saat edit jika tidak ingin mengubah
                                password.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-t border-gray-200">
                        <button type="button"
                            class="px-4 py-2.5 bg-green-50 border border-border rounded-xl text-text-main hover:bg-green-100 transition-colors font-medium"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="px-4 py-2.5 bg-green-700 rounded-xl text-white font-medium hover:bg-green-800 transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const userModalEl = document.getElementById('userModal');
        const userModal = new bootstrap.Modal(userModalEl);
        const form = document.getElementById('userForm');
        const modalTitle = document.getElementById('userModalLabel');

        // Fungsi untuk Reset Form
        function resetForm() {
            modalTitle.textContent = 'Tambah User Baru';
            form.action = '{{ route('admin.users.store') }}';
            document.getElementById('userFormMethod').value = 'POST';
            document.getElementById('u_name').value = '';
            document.getElementById('u_email').value = '';
            document.getElementById('u_phone').value = '';
            document.getElementById('u_role').value = 'siswa';
            document.getElementById('u_status').value = 'menunggu';
            document.getElementById('u_password').value = '';
            document.getElementById('u_password').required = true;
        }

        document.getElementById('btnAddUser').addEventListener('click', function() {
            resetForm();
            userModal.show();
        });

        // Fungsi Edit User
        function openEditUser(user) {
            modalTitle.textContent = 'Edit User';
            form.action = '/admin/users/' + user.id;
            document.getElementById('userFormMethod').value = 'PUT';

            document.getElementById('u_name').value = user.name || '';
            document.getElementById('u_email').value = user.email || '';
            document.getElementById('u_phone').value = user.phone || '';
            document.getElementById('u_role').value = user.role || 'siswa';
            document.getElementById('u_status').value = user.status_akun || 'menunggu';
            document.getElementById('u_password').value = '';
            document.getElementById('u_password').required = false;

            userModal.show();
        }

        function confirmDelete(e, el) {
            e.preventDefault();

            Swal.fire({
                title: "Hapus data?",
                text: "Data user yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    el.closest("form").submit();
                }
            });
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

            // Inisialisasi DataTables
            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                $('#usersTable').DataTable({
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
                    }],
                    language: {
                        search: "",
                        searchPlaceholder: "Cari user...",
                        lengthMenu: "Tampilkan _MENU_ Data",
                        paginate: {
                            previous: "<",
                            next: ">"
                        },
                        info: "Menampilkan _START_–_END_ dari _TOTAL_ Data",
                        zeroRecords: "Tidak ada data",
                    }
                });
            }
        });
    </script>
@endpush
