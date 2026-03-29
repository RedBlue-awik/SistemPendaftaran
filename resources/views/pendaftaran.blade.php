<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - SMK MAMBA'UL IHSAN</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

    <!-- FilePond CSS -->
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.css">

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

        /* FilePond Styles */
        .filepond--root {
            min-height: 7rem;
        }

        .filepond--panel-root {
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 0.75rem;
        }

        .filepond--drop-label {
            color: #6b7280;
        }

        .filepond--label-action {
            color: #16a34a;
            text-decoration: underline;
        }

        /* Flatpickr Fix: Agar input mengikuti lebar penuh */
        .flatpickr-wrapper {
            width: 100%;
            display: block;
        }

        .flatpickr-calendar {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-700 font-poppins">

    <header class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}"
                class="text-[18px] md:text-2xl font-bold text-hijau-gelap flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" class="w-12 h-12" alt="Logo"> SMK MAMBA'UL IHSAN
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

                <ul class="progress-bar-container relative flex justify-between mb-12 px-4">
                    <li class="progress-step relative z-10 text-center flex-1" data-step="1">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            1</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400">Data Diri</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="2">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            2</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400">Akademik</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="3">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            3</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400">Program</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="4">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            4</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400">Dokumen</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="5">
                        <div
                            class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">
                            5</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400">Konfirmasi</p>
                    </li>
                </ul>

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

                    <div class="form-step active" id="step1">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 1: Data Diri</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition"
                                    required value="{{ old('nama_lengkap') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">NIK</label>
                                <input type="text" name="nik" pattern="[0-9]{16}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition"
                                    required value="{{ old('nik') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition bg-white"
                                    required>
                                    <option value="" selected disabled>Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                                <input type="text" name="tanggal_lahir" id="tanggalLahir"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition bg-white"
                                    placeholder="Pilih Tanggal" required value="{{ old('tanggal_lahir') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Lengkap</label>
                                <textarea name="alamat" rows="2"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition"
                                    required>{{ old('alamat') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Email Aktif</label>
                                <input type="email" name="email"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition cursor-not-allowed"
                                    required value="{{ $user->email }}" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">No. HP (WhatsApp)</label>
                                <input type="tel" name="no_hp" pattern="[0-9]{10,13}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition"
                                    required value="{{ str_replace('+62', '0', $user->phone) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-step" id="step2">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 2: Data Akademik</h4>
                        <div class="grid gap-4 md:gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Asal Sekolah</label>
                                <input type="text" name="sekolah_asal"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition"
                                    required value="{{ old('sekolah_asal') }}">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Sekolah</label>
                                    <select name="jenis_sekolah"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition bg-white"
                                        required>
                                        <option value="" selected disabled>Pilih...</option>
                                        <option value="SMP">SMP</option>
                                        <option value="MTS">MTS</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Tahun Lulus</label>
                                    <input type="text" name="tahun_lulus"
                                        class="flatpickr-year w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition bg-white"
                                        required value="{{ old('tahun_lulus') }}" placeholder="Pilih Tahun">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-step" id="step3">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 3: Pilihan Program Studi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Gelombang</label>
                                @if (isset($activeGelombang) && $activeGelombang)
                                    <input type="hidden" name="gelombang_id" value="{{ $activeGelombang->id }}">
                                    <div
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                        {{ $activeGelombang->nama }} ({{ $activeGelombang->tanggal_mulai }} -
                                        {{ $activeGelombang->tanggal_selesai }}) @if (!is_null($activeGelombang->batas_pendaftaran))
                                            <span class="text-sm text-gray-500">(Sisa:
                                                {{ $activeGelombang->batas_pendaftaran }})</span>
                                        @endif
                                    </div>
                                @else
                                    <div
                                        class="w-full px-4 py-2.5 border border-red-200 rounded-lg bg-red-50 text-red-600">
                                        Tidak ada gelombang aktif saat ini.</div>
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Jalur</label>
                                <select name="jalur_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition bg-white"
                                    required>
                                    <option value="" selected disabled>-- Pilih Jalur --</option>
                                    @foreach ($jalurs as $j)
                                        @php
                                            $remaining = $j->batas_pendaftaran; // now treated as remaining kuota
                                            $isFull = !is_null($remaining) && $remaining <= 0;
                                        @endphp
                                        <option value="{{ $j->id }}"
                                            {{ old('jalur_id') == $j->id ? 'selected' : '' }}
                                            @if ($isFull) disabled @endif>{{ $j->nama_jalur }}
                                            @if ($isFull)
                                                (Penuh)
                                            @elseif(!is_null($remaining))
                                                (Sisa: {{ $remaining }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Jurusan Pilihan</label>
                                <select name="jurusan_pilihan"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama transition bg-white"
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

                    <div class="form-step" id="step4">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 4: Upload Dokumen</h4>
                        <p class="text-gray-500 mb-6 text-sm">Silakan unggah dokumen dalam format <strong>PNG,
                                JPG</strong> atau <strong>PDF</strong> (Maks. 2MB).</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="bi bi-file-earmark-pdf-fill text-red-500"></i> Kartu Keluarga (KK)
                                    </label>
                                    <small class="text-xs text-gray-400">Format: PDF/JPG/PNG</small>
                                </div>
                                <input type="file" name="kk" class="filepond"
                                    accept="image/png,image/jpeg,application/pdf">
                            </div>
                            <div>
                                <div class="mb-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="bi bi-mortarboard-fill text-blue-500"></i> Ijazah / SKL
                                    </label>
                                    <small class="text-xs text-gray-400">Format: PDF/JPG/PNG</small>
                                </div>
                                <input type="file" name="ijazah" class="filepond"
                                    accept="image/png,image/jpeg,application/pdf">
                            </div>
                            <div>
                                <div class="mb-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="bi bi-file-text-fill text-gray-700"></i> Akta Kelahiran
                                    </label>
                                    <small class="text-xs text-gray-400">Format: PDF/JPG/PNG</small>
                                </div>
                                <input type="file" name="akta" class="filepond"
                                    accept="image/png,image/jpeg,application/pdf">
                            </div>
                            <div>
                                <div class="mb-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="bi bi-person-bounding-box text-purple-500"></i> Pas Foto 3x4
                                    </label>
                                    <small class="text-xs text-gray-400">Format: JPG/PNG (Berwarna)</small>
                                </div>
                                <input type="file" name="foto" class="filepond" accept="image/png,image/jpeg">
                            </div>
                            <div>
                                <div class="mb-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="bi bi-person-vcard-fill text-orange-500"></i> KTP Orangtua
                                    </label>
                                    <small class="text-xs text-gray-400">Format: PDF/JPG/PNG</small>
                                </div>
                                <input type="file" name="ktp_orangtua" class="filepond"
                                    accept="image/png,image/jpeg,application/pdf">
                            </div>
                            <div>
                                <div class="mb-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="bi bi-award-fill text-yellow-500"></i> KIP / Prestasi (Opsional)
                                    </label>
                                    <small class="text-xs text-gray-400">Format: PDF/JPG/PNG</small>
                                </div>
                                <input type="file" name="kip" class="filepond"
                                    accept="image/png,image/jpeg,application/pdf">
                            </div>
                        </div>
                    </div>

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
                                pendaftaran.
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" id="prevBtn"
                            class="hidden bg-gray-500 hover:bg-gray-600 text-white font-medium py-2.5 px-6 rounded-lg transition duration-300">Sebelumnya</button>
                        <button type="button" id="nextBtn"
                            class="ml-auto bg-hijau-utama hover:bg-hijau-gelap text-white font-medium py-2.5 px-6 rounded-lg transition duration-300">Selanjutnya</button>
                        <button type="submit" id="submitBtn"
                            class="ml-4 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-6 rounded-lg transition duration-300 hidden">Kirim
                            Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

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
            timerProgressBar: true
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
            let current = 1;
            const agreeCheckbox = document.getElementById('agreeTerms');
            const submitBtn = document.getElementById('submitBtn');

            // default: disable dulu
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            agreeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });

            function show(i) {
                steps.forEach((s, idx) => s.classList.toggle('active', idx + 1 === i));
                progressSteps.forEach((p, idx) => {
                    const icon = p.querySelector('.step-icon');
                    const text = p.querySelector('p');
                    icon.className =
                        'step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 ' +
                        (idx + 1 < i ? 'bg-hijau-gelap' : (idx + 1 === i ? 'bg-hijau-utama' : 'bg-gray-300'));
                    text.className = (idx + 1 < i ? 'mt-2 text-xs md:text-sm font-medium text-hijau-gelap' : (
                        idx + 1 === i ? 'mt-2 text-xs md:text-sm font-medium text-hijau-utama' :
                        'mt-2 text-xs md:text-sm font-medium text-gray-400'));
                });
                prevBtn.classList.toggle('hidden', i === 1);
                nextBtn.classList.toggle('hidden', i === steps.length);
                document.getElementById('submitBtn').classList.toggle('hidden', i !== steps.length);
                if (i === steps.length) updateReview();
            }

            function updateReview() {
                document.getElementById('reviewNama').innerText = document.querySelector('[name="nama_lengkap"]').value;
                document.getElementById('reviewEmail').innerText = document.querySelector('[name="email"]').value;
                document.getElementById('reviewNoHp').innerText = document.querySelector('[name="no_hp"]').value;
                document.getElementById('reviewSekolah').innerText = document.querySelector('[name="sekolah_asal"]')
                    .value;
                document.getElementById('reviewPilihan').innerText = document.querySelector('[name="jurusan_pilihan"]')
                    .value;
            }

            function validateCurrentStep() {
                const currentStepElement = steps[current - 1];
                const inputs = currentStepElement.querySelectorAll(
                    'input[required], select[required], textarea[required]');
                let isValid = true;
                inputs.forEach(input => {
                    if (input.type === 'file') return;
                    if (!input.value) {
                        input.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });
                return isValid;
            }

            if (nextBtn) nextBtn.addEventListener('click', () => {
                if (current < steps.length && !validateCurrentStep()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Harap isi semua field wajib.'
                    });
                    return;
                }
                current = Math.min(steps.length, current + 1);
                show(current);
                window.scrollTo(0, 0);
            });

            if (prevBtn) prevBtn.addEventListener('click', () => {
                current = Math.max(1, current - 1);
                show(current);
                window.scrollTo(0, 0);
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!agreeCheckbox.checked) {
                    Swal.fire('Perhatian', 'Anda harus menyetujui pernyataan.', 'warning');
                    return;
                }

                const requiredFiles = ['kk', 'ijazah', 'akta', 'foto', 'ktp_orangtua'];
                let missingFiles = [];
                requiredFiles.forEach(name => {
                    if (!pondInstances[name] || pondInstances[name].getFiles().length === 0)
                        missingFiles.push(name.toUpperCase());
                });

                if (missingFiles.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dokumen Belum Lengkap',
                        html: `Harap unggah dokumen berikut: <br><b>${missingFiles.join(', ')}</b>`
                    });
                    return;
                }

                Loading.fire();

                const formData = new FormData();
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const textInputs = form.querySelectorAll('input:not([type="file"]), select, textarea');
                textInputs.forEach(input => {
                    if (input.name) formData.append(input.name, input.value);
                });

                for (const [name, pond] of Object.entries(pondInstances)) {
                    const files = pond.getFiles();
                    if (files.length > 0) formData.append(name, files[0].file);
                }

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (response.ok && data.redirect) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Pendaftaran tersimpan.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        } else if (response.status === 422) {
                            let errors = data.errors;
                            let msg = Object.values(errors).flat().join('<br>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                html: msg
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

            document.addEventListener('DOMContentLoaded', function() {
                if (window.flatpickr) {
                    // Inisialisasi Tahun Lulus
                    flatpickr('.flatpickr-year', {
                        plugins: [new monthSelectPlugin({
                            shorthand: true,
                            dateFormat: 'Y',
                            altFormat: 'Y'
                        })],
                        altInput: true,
                        altFormat: 'Y',
                        dateFormat: 'Y',
                        maxDate: 'today'
                    });

                    // Inisialisasi Tanggal Lahir (Fix Full Width)
                    flatpickr('#tanggalLahir', {
                        dateFormat: 'Y-m-d',
                        maxDate: 'today',
                        altInput: true,
                        altFormat: 'j F Y',
                        allowInput: true
                    });
                }

                if (window.FilePond) {
                    FilePond.registerPlugin(FilePondPluginFileValidateType, FilePondPluginFileValidateSize);

                    FilePond.setOptions({
                        labelIdle: 'Seret & lepas atau <span class="filepond--label-action">Klik disini</span>',
                        labelFileLoading: 'Memuat...',
                        labelFileLoadError: 'Error saat memuat',
                        labelFileProcessing: 'Mengunggah...',
                        labelFileProcessingComplete: 'Unggah selesai',
                        labelFileProcessingAborted: 'Unggah dibatalkan',
                        labelFileProcessingError: 'Error saat mengunggah',
                        labelTapToCancel: 'Batalkan',
                        labelTapToRetry: 'Coba lagi',
                        labelTapToUndo: 'Undo',
                        labelButtonRemoveItem: 'Hapus',
                        labelButtonAbortItemLoad: 'Batal',
                        labelButtonRetryItemLoad: 'Coba lagi',
                        labelMaxFileSizeExceeded: 'File terlalu besar!',
                        labelMaxFileSize: 'Ukuran file maksimal adalah {filesize}',
                        labelFileTypeNotAllowed: 'Tipe file tidak valid!',
                        fileValidateTypeLabelExpectedTypes: 'Format yang diizinkan: {allButLastType} atau {lastType}'
                    });

                    const inputs = document.querySelectorAll('input.filepond');
                    inputs.forEach(input => {
                        const pond = FilePond.create(input, {
                            server: null,
                            instantUpload: false,
                            allowProcess: false,
                            maxFileSize: '2MB',
                            acceptedFileTypes: input.accept ? input.accept.split(',') : [
                                'image/png', 'image/jpeg', 'application/pdf'
                            ]
                        });
                        pondInstances[input.name] = pond;
                    });
                }
                show(current);
            });
        })();
    </script>
</body>

</html>
