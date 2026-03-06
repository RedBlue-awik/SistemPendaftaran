<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - SPMB Universitas Hijau</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Flatpickr CSS & FilePond CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.css">
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .progress-bar-container::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            height: 4px;
            width: 100%;
            background-color: #e0e0e0;
            z-index: 0;
        }

        .form-step {
            display: none;
            opacity: 0;
            transform: translateY(10px);
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.5s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        input[type="file"] {
            display: none;
        }

        .filepond--root {
            min-height: 7rem;
        }

        .filepond--panel {
            min-height: 7rem;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-700 font-poppins">

    <!-- Navigation -->
    <header class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.html" class="text-[18px] md:text-2xl font-bold text-hijau-gelap flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" class="w-12 h-12" alt="SMK MAMBA'UL IHSAN"> SMK MAMBA'UL IHSAN
            </a>
            <a href="{{ route('logout') }}"
                class="bg-hijau-utama hover:bg-hijau-gelap text-white px-5 py-2 rounded-full text-sm font-medium transition duration-300 flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </header>

    <main class="pt-24 pb-12 px-4">
        <div class="container mx-auto max-w-4xl">
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-10">
                <h2 class="text-2xl md:text-3xl font-semibold text-hijau-gelap text-center mb-10">Formulir Pendaftaran
                    Murid Baru</h2>

                <!-- Progress Bar -->
                <ul class="progress-bar-container relative flex justify-between mb-12 px-4">
                    <li class="progress-step relative z-10 text-center flex-1" data-step="1">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            1</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">Data
                            Diri</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="2">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            2</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">
                            Akademik</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="3">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            3</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">Program
                        </p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="4">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            4</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">Dokumen
                        </p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="5">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            5</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">
                            Konfirmasi</p>
                    </li>
                </ul>

                <!-- Multi-Step Form -->
                <form id="regForm" method="POST" action="{{ route('pendaftaran.store') }}"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Step 1: Data Diri -->
                    <div class="form-step active" id="step1">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 1: Data Diri</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label for="namaLengkap" class="block text-sm font-medium text-gray-600 mb-1">Nama
                                    Lengkap *</label>
                                <input type="text" id="namaLengkap" name="nama_lengkap"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                    required value="{{ old('nama_lengkap') }}">
                            </div>
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-600 mb-1">NIK *</label>
                                <input type="text" id="nik" name="nik" pattern="[0-9]{16}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                    required value="{{ old('nik') }}">
                            </div>
                            <div>
                                <label for="jenisKelamin" class="block text-sm font-medium text-gray-600 mb-1">Jenis
                                    Kelamin *</label>
                                <select id="jenisKelamin" name="jenis_kelamin"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white"
                                    required>
                                    <option value="" selected disabled>Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="tanggalLahir" class="block text-sm font-medium text-gray-600 mb-1">Tanggal
                                    Lahir *</label>
                                <input type="date" id="tanggalLahir" name="tanggal_lahir"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                    required value="{{ old('tanggal_lahir') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-600 mb-1">Alamat
                                    Lengkap *</label>
                                <textarea id="alamat" name="alamat" rows="2"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                    required>{{ old('alamat') }}</textarea>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Email Aktif
                                    *</label>
                                <input type="email" id="email" name="email"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                    required value="{{ old('email') }}">
                            </div>
                            <div>
                                <label for="noHp" class="block text-sm font-medium text-gray-600 mb-1">No. HP
                                    (WhatsApp Aktif) *</label>
                                <input type="tel" id="noHp" name="no_hp" pattern="[0-9]{10,13}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                    required value="{{ old('no_hp') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Data Akademik -->
                    <div class="form-step" id="step2">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 2: Data Akademik</h4>
                        <div class="grid gap-4 md:gap-6">
                            <div>
                                <label for="namaSekolah" class="block text-sm font-medium text-gray-600 mb-1">Nama
                                    Asal Sekolah *</label>
                                <input type="text" id="namaSekolah" name="sekolah_asal"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                    required value="{{ old('sekolah_asal') }}">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="jenisSekolah"
                                        class="block text-sm font-medium text-gray-600 mb-1">Jenis Sekolah *</label>
                                    <select id="jenisSekolah" name="jenis_sekolah"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white"
                                        required>
                                        <option value="" selected disabled>Pilih...</option>
                                        <option value="SMP">SMP</option>
                                        <option value="MTS">MTS</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="tahunLulus" class="block text-sm font-medium text-gray-600 mb-1">Tahun
                                        Lulus *</label>
                                    <input type="text" id="tahunLulus" name="tahun_lulus"
                                        class="flatpickr-year w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white"
                                        required value="{{ old('tahun_lulus') }}" placeholder="Pilih tahun">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Pilihan Program -->
                    <div class="form-step" id="step3">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 3: Pilihan Program Studi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label for="gelombang" class="block text-sm font-medium text-gray-600 mb-1">Gelombang
                                    *</label>
                                <select id="gelombang" name="gelombang_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white"
                                    required>
                                    <option value="" selected disabled>-- Pilih Gelombang --</option>
                                    @foreach ($gelombangs as $g)
                                        <option value="{{ $g->id }}"
                                            {{ old('gelombang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }}
                                            ({{ $g->tanggal_mulai }} - {{ $g->tanggal_selesai }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="jalur" class="block text-sm font-medium text-gray-600 mb-1">Jalur
                                    *</label>
                                <select id="jalur" name="jalur_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white"
                                    required>
                                    <option value="" selected disabled>-- Pilih Jalur --</option>
                                    @foreach ($jalurs as $j)
                                        <option value="{{ $j->id }}"
                                            {{ old('jalur_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jalur }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="jurusan" class="block text-sm font-medium text-gray-600 mb-1">Jurusan
                                    Pilihan *</label>
                                <select id="jurusan" name="jurusan_pilihan"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white"
                                    required>
                                    <option value="" selected disabled>-- Pilih Jurusan --</option>
                                    <option value="RPL">RPL</option>
                                    <option value="ATPH">ATPH</option>
                                    <option value="BUSANA">BUSANA</option>
                                    <option value="KULINER">KULINER</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Upload Dokumen -->
                    <div class="form-step" id="step4">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 4: Upload Dokumen</h4>
                        <p class="text-gray-500 mb-6 text-sm">Silakan unggah dokumen dalam format PNG, JPG, atau PDF
                            (Maks. 2MB).</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- KK -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">KK (Kartu Keluarga)</label>
                                <label for="kk"
                                    class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="kk-label">Klik untuk unggah
                                        file</span>
                                </label>
                                <input type="file" id="kk" name="kk" class="filepond" required>
                            </div>

                            <!-- Ijazah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Ijazah / SKL</label>
                                <label for="ijazah"
                                    class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="ijazah-label">Klik untuk unggah
                                        file</span>
                                </label>
                                <input type="file" id="ijazah" name="ijazah" class="filepond" required>
                            </div>

                            <!-- Akta -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Akta Kelahiran</label>
                                <label for="akta"
                                    class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="akta-label">Klik untuk unggah
                                        file</span>
                                </label>
                                <input type="file" id="akta" name="akta" class="filepond" required>
                            </div>

                            <!-- Pas Foto -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Pas Foto 3x4
                                    (berwarna)</label>
                                <label for="foto"
                                    class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="foto-label">Klik untuk unggah
                                        file</span>
                                </label>
                                <input type="file" id="foto" name="foto" class="filepond"
                                    accept="image/png,image/jpeg" required>
                            </div>

                            <!-- Sertifikat (opsional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Sertifikat
                                    (opsional)</label>
                                <label for="sertifikat"
                                    class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="sertifikat-label">Klik untuk unggah
                                        file</span>
                                </label>
                                <input type="file" id="sertifikat" name="sertifikat" class="filepond">
                            </div>

                            <!-- KTP Orangtua (opsional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">KTP Orangtua
                                    (opsional)</label>
                                <label for="ktp_orangtua"
                                    class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="ktp_orangtua-label">Klik untuk unggah
                                        file</span>
                                </label>
                                <input type="file" id="ktp_orangtua" name="ktp_orangtua" class="filepond">
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Konfirmasi -->
                    <div class="form-step" id="step5">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 5: Konfirmasi Data</h4>
                        <p class="text-gray-500 mb-4 text-sm">Mohon periksa kembali data Anda.</p>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left mb-6">
                                <tbody>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700 w-1/3">Nama Lengkap
                                        </th>
                                        <td class="py-3 px-4" id="reviewNama"></td>
                                    </tr>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Email</th>
                                        <td class="py-3 px-4" id="reviewEmail"></td>
                                    </tr>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">No. HP</th>
                                        <td class="py-3 px-4" id="reviewNoHp"></td>
                                    </tr>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Asal Sekolah</th>
                                        <td class="py-3 px-4" id="reviewSekolah"></td>
                                    </tr>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Jurusan Pilihan
                                        </th>
                                        <td class="py-3 px-4" id="reviewPilihan"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-start gap-3">
                            <input id="agreeTerms" type="checkbox"
                                class="w-5 h-5 text-hijau-utama border-gray-300 rounded focus:ring-hijau-utama cursor-pointer">
                            <label for="agreeTerms" class="text-sm text-gray-600 cursor-pointer">
                                Saya menyatakan bahwa data yang diisi adalah benar dan bersedia mematuhi semua peraturan
                                pendaftaran. *
                            </label>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8">
                        <button type="button" id="prevBtn"
                            class="hidden bg-gray-500 hover:bg-gray-600 text-white font-medium py-2.5 px-6 rounded-lg transition duration-300">
                            Sebelumnya
                        </button>
                        <button type="button" id="nextBtn"
                            class="ml-auto bg-hijau-utama hover:bg-hijau-gelap text-white font-medium py-2.5 px-6 rounded-lg transition duration-300">
                            Selanjutnya
                        </button>
                        <button type="submit" id="submitBtn"
                            class="ml-4 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-6 rounded-lg transition duration-300 hidden">Kirim
                            Pendaftaran</button>
                        <noscript>
                            <button type="submit"
                                class="ml-auto bg-hijau-gelap hover:bg-green-900 text-white font-medium py-3 px-8 rounded-lg text-lg transition duration-300">Kirim
                                Pendaftaran</button>
                        </noscript>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Success Modal (Pure Tailwind) -->
    <div id="successModal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-8 text-center transform transition-all">
            <div class="text-green-500 mb-4">
                <i class="bi bi-check-circle-fill text-6xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Pendaftaran Berhasil!</h3>
            <p class="text-gray-600 mb-6">Terima kasih, data Anda telah kami terima. Silakan cek no WhatsApp Anda
                secara berkala untuk informasi selanjutnya.</p>
            <button id="closeModalBtn"
                class="bg-hijau-utama hover:bg-hijau-gelap text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                Tutup
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        const Loading = Swal.mixin({
            title: 'Mengirim Data...',
            html: 'Sedang mengunggah file dan data pendaftaran.<br><small>Mohon tunggu.</small>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const pondInstances = {};

        (function() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const steps = Array.from(document.querySelectorAll('.form-step'));
            const progressSteps = Array.from(document.querySelectorAll('.progress-step'));
            const form = document.getElementById('regForm');
            let current = parseInt(localStorage.getItem('pendaftaran_step')) || 1;
            const agreeCheckbox = document.getElementById('agreeTerms');
            const submitBtnGlobal = document.getElementById('submitBtn');

            function updateSubmitState() {
                if (!submitBtnGlobal) return;
                if (!agreeCheckbox) {
                    submitBtnGlobal.disabled = false;
                    submitBtnGlobal.classList.remove('opacity-50');
                    return;
                }
                submitBtnGlobal.disabled = !agreeCheckbox.checked;
                submitBtnGlobal.classList.toggle('opacity-50', !agreeCheckbox.checked);
            }

            function show(i) {
                steps.forEach((s, idx) => s.classList.toggle('active', idx + 1 === i));
                progressSteps.forEach((p, idx) => {
                    const icon = p.querySelector('.step-icon');
                    const text = p.querySelector('p');
                    icon.className =
                        'step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 ' +
                        (idx + 1 < i ? 'bg-hijau-gelap' : (idx + 1 === i ? 'bg-hijau-utama' : 'bg-gray-300'));
                    text.className = (idx + 1 < i ?
                        'mt-2 text-xs md:text-sm font-medium text-hijau-gelap transition-all duration-400' :
                        (idx + 1 === i ?
                            'mt-2 text-xs md:text-sm font-medium text-hijau-utama transition-all duration-400' :
                            'mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400')
                    );
                });
                prevBtn.classList.toggle('hidden', i === 1);
                nextBtn.classList.toggle('hidden', i === steps.length);
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) submitBtn.classList.toggle('hidden', i !== steps.length);
                updateSubmitState();
                localStorage.setItem('pendaftaran_step', i);
            }

            function saveFormData() {
                if (!form) return;
                const fd = new FormData(form);
                const data = {};
                fd.forEach((v, k) => {
                    const el = form.querySelector(`[name="${k}"]`);
                    if (!el) return;
                    if (el.type === 'file') return;
                    if (el.type === 'checkbox') data[k] = el.checked;
                    else data[k] = v;
                });
                try {
                    localStorage.setItem('pendaftaran_data', JSON.stringify(data));
                } catch (e) {}
            }

            function restoreFormData() {
                if (!form) return;
                const raw = localStorage.getItem('pendaftaran_data');
                if (!raw) return;
                try {
                    const data = JSON.parse(raw);
                    Object.keys(data).forEach(k => {
                        const el = form.querySelector(`[name="${k}"]`);
                        if (!el) return;
                        if (el.type === 'checkbox') el.checked = !!data[k];
                        else el.value = data[k];
                    });
                } catch (e) {}
            }

            if (form) {
                form.addEventListener('input', saveFormData);
                form.addEventListener('change', saveFormData);

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    let isValid = true;
                    let firstErrorStep = 1;
                    const stepEls = Array.from(document.querySelectorAll('.form-step'));

                    for (let i = 0; i < stepEls.length; i++) {
                        const inputs = stepEls[i].querySelectorAll(
                            'input[required], select[required], textarea[required]');
                        for (const input of inputs) {
                            if (input.type === 'file') {
                                const pond = pondInstances[input.name];
                                let hasFile = pond ? pond.getFiles().length > 0 : false;
                                if (!hasFile) {
                                    isValid = false;
                                    firstErrorStep = i + 1;
                                    break;
                                }
                            } else if (input.type === 'checkbox') {
                                if (!input.checked) {
                                    isValid = false;
                                    firstErrorStep = i + 1;
                                    break;
                                }
                            } else {
                                if (!input.value) {
                                    isValid = false;
                                    firstErrorStep = i + 1;
                                    break;
                                }
                            }
                        }
                        if (!isValid) break;
                    }

                    if (!isValid) {
                        current = firstErrorStep;
                        show(current);
                        saveFormData();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Belum Lengkap',
                            text: 'Harap isi semua field wajib dan upload dokumen.'
                        });
                        return;
                    }

                    const formData = new FormData();

                    const textInputs = form.querySelectorAll('input:not([type="file"]), select, textarea');
                    textInputs.forEach(input => {
                        if (input.name) formData.append(input.name, input.value);
                    });

                    Object.keys(pondInstances).forEach(name => {
                        const pond = pondInstances[name];
                        const files = pond.getFiles();
                        if (files.length > 0) {
                            formData.append(name, files[0].file, files[0].filename);
                        }
                    });

                    Loading.fire();

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content') || document.querySelector(
                                        'input[name="_token"]').value
                            }
                        })
                        .then(async response => {
                            const data = await response.json();
                            if (response.ok && data.redirect) {
                                localStorage.removeItem('pendaftaran_data');
                                localStorage.removeItem('pendaftaran_step');
                                window.location.href = data.redirect;
                            } else if (response.status === 422) {
                                let errors = data.errors;
                                let msg = '';
                                for (let key in errors) {
                                    msg += errors[key].join(', ') + '\n';
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal',
                                    text: msg
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: data.message || 'Terjadi kesalahan server.'
                                });
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Koneksi',
                                text: 'Tidak dapat terhubung ke server.'
                            });
                        });
                });
            }

            if (agreeCheckbox) agreeCheckbox.addEventListener('change', updateSubmitState);

            function validateCurrentStep() {
                const currentStepElement = steps[current - 1];
                const inputs = currentStepElement.querySelectorAll(
                    'input[required], select[required], textarea[required]');
                let isValid = true;
                inputs.forEach(input => {
                    if (input.type === 'file' || input.type === 'checkbox') return;
                    if (!input.value) {
                        input.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });
                if (!isValid) Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap lengkapi field.',
                    confirmButtonColor: '#16a34a'
                });
                return isValid;
            }

            if (nextBtn) nextBtn.addEventListener('click', () => {
                if (current < steps.length && !validateCurrentStep()) return;
                current = Math.min(steps.length, current + 1);
                show(current);
                if (current === steps.length) updateReview();
                saveFormData();
                window.scrollTo(0, 0);
            });

            if (prevBtn) prevBtn.addEventListener('click', () => {
                current = Math.max(1, current - 1);
                show(current);
                saveFormData();
                window.scrollTo(0, 0);
            });

            restoreFormData();
            show(current);
            updateSubmitState();
        })();

        function updateReview() {
            const form = document.getElementById('regForm');
            if (!form) return;
            const getVal = (name) => {
                const el = form.querySelector(`[name="${name}"]`);
                if (!el) return '';
                if (el.tagName === 'SELECT') return el.options[el.selectedIndex]?.text || '';
                return el.value || '';
            }
            document.getElementById('reviewNama').innerText = getVal('nama_lengkap');
            document.getElementById('reviewEmail').innerText = getVal('email');
            document.getElementById('reviewNoHp').innerText = getVal('no_hp');
            document.getElementById('reviewSekolah').innerText = getVal('sekolah_asal');
            document.getElementById('reviewPilihan').innerText = getVal('jurusan_pilihan');
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.flatpickr && window.monthSelectPlugin) {
                flatpickr('.flatpickr-year', {
                    plugins: [new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: 'Y',
                        altFormat: 'Y'
                    })],
                    altInput: true,
                    altFormat: 'Y',
                    dateFormat: 'Y',
                    defaultDate: '{{ old('tahun_lulus') ?? '' }}',
                    maxDate: 'today',
                });
                flatpickr('#tanggalLahir', {
                    dateFormat: 'Y-m-d',
                    defaultDate: '{{ old('tanggal_lahir') ?? '' }}'
                });
            }

            if (window.FilePond) {
                if (window.FilePondPluginFileValidateType) FilePond.registerPlugin(FilePondPluginFileValidateType);
                if (window.FilePondPluginFileValidateSize) FilePond.registerPlugin(FilePondPluginFileValidateSize);

                FilePond.setOptions({
                    server: null,
                    allowProcess: false
                });

                const commonOpts = {
                    allowMultiple: false,
                    maxFileSize: '2MB',
                    acceptedFileTypes: ['image/png', 'image/jpeg', 'application/pdf'],
                    labelFileTypeNotAllowed: 'Tipe file tidak diizinkan.',
                    fileValidateTypeLabelExpectedTypes: 'Harap pilih file berformat PNG, JPG atau PDF.'
                };

                document.querySelectorAll('input[type="file"]').forEach(function(input) {
                    const opts = Object.assign({}, commonOpts);
                    if (input.name === 'foto') {
                        opts.acceptedFileTypes = ['image/png', 'image/jpeg'];
                        opts.fileValidateTypeLabelExpectedTypes = 'Harap pilih file gambar (PNG/JPG).';
                    }
                    const pond = FilePond.create(input, opts);
                    pondInstances[input.name] = pond;
                });
            }

            @if (session('success'))
                Toast.fire({
                    icon: "success",
                    title: "{{ session('success') }}"
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}"
                });
            @endif
            @if ($errors->any())
                let msgs = "";
                @foreach ($errors->all() as $err)
                    msgs += "{{ $err }} ";
                @endforeach
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: msgs
                });
            @endif
        });
    </script>
</body>

</html>
