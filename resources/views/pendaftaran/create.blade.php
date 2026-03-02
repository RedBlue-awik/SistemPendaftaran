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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            to { opacity: 1; transform: translateY(0); }
        }

        input[type="file"] {
            display: none;
        }
        /* Ensure FilePond UI matches the original upload label height */
        .filepond--root {
            min-height: 7rem; /* roughly h-28 */
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
                <i class="bi bi-mortarboard-fill"></i> SMK MAMBA'UL IHSAN
            </a>
            <a href="{{ route('logout') }}" class="bg-hijau-utama hover:bg-hijau-gelap text-white px-5 py-2 rounded-full text-sm font-medium transition duration-300 flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </header>

    <main class="pt-24 pb-12 px-4">
        <div class="container mx-auto max-w-4xl">
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-10">
                <h2 class="text-2xl md:text-3xl font-semibold text-hijau-gelap text-center mb-10">Formulir Pendaftaran Murid Baru</h2>
                
                <!-- Progress Bar -->
                <ul class="progress-bar-container relative flex justify-between mb-12 px-4">
                    <li class="progress-step relative z-10 text-center flex-1" data-step="1">
                        <div class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">1</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">Data Diri</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="2">
                        <div class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">2</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">Akademik</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="3">
                        <div class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">3</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">Program</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="4">
                        <div class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">4</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">Dokumen</p>
                    </li>
                    <li class="progress-step relative z-10 text-center flex-1" data-step="5">
                        <div class="step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 bg-gray-300">5</div>
                        <p class="mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400">Konfirmasi</p>
                    </li>
                </ul>

                <!-- Multi-Step Form -->
                <form id="regForm" method="POST" action="{{ route('pendaftaran.confirm') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Step 1: Data Diri -->
                    <div class="form-step active" id="step1">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 1: Data Diri</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label for="namaLengkap" class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap *</label>
                                <input type="text" id="namaLengkap" name="nama_lengkap" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition" required value="{{ old('nama_lengkap') }}">
                            </div>
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-600 mb-1">NIK *</label>
                                <input type="text" id="nik" name="nik" pattern="[0-9]{16}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition" required value="{{ old('nik') }}">
                            </div>
                            <div>
                                <label for="jenisKelamin" class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin *</label>
                                <select id="jenisKelamin" name="jenis_kelamin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white" required>
                                    <option value="" selected disabled>Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="tanggalLahir" class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir *</label>
                                <input type="date" id="tanggalLahir" name="tanggal_lahir" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition" required value="{{ old('tanggal_lahir') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-600 mb-1">Alamat Lengkap *</label>
                                <textarea id="alamat" name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition" required>{{ old('alamat') }}</textarea>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Email Aktif *</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition" required value="{{ old('email') }}">
                            </div>
                            <div>
                                <label for="noHp" class="block text-sm font-medium text-gray-600 mb-1">No. HP (WhatsApp Aktif) *</label>
                                <input type="tel" id="noHp" name="no_hp" pattern="[0-9]{10,13}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition" required value="{{ old('no_hp') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Data Akademik -->
                    <div class="form-step" id="step2">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 2: Data Akademik</h4>
                        <div class="grid gap-4 md:gap-6">
                            <div>
                                <label for="namaSekolah" class="block text-sm font-medium text-gray-600 mb-1">Nama Asal Sekolah *</label>
                                <input type="text" id="namaSekolah" name="sekolah_asal" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition" required value="{{ old('sekolah_asal') }}">
                            </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label for="jenisSekolah" class="block text-sm font-medium text-gray-600 mb-1">Jenis Sekolah *</label>
                                <select id="jenisSekolah" name="jenis_sekolah" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white" required>
                                    <option value="" selected disabled>Pilih...</option>
                                    <option value="SMP">SMP</option>
                                    <option value="MTS">MTS</option>
                                </select>
                            </div>
                            <div>
                                <label for="tahunLulus" class="block text-sm font-medium text-gray-600 mb-1">Tahun Lulus *</label>
                                <input type="text" id="tahunLulus" name="tahun_lulus" class="flatpickr-year w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white" required value="{{ old('tahun_lulus') }}" placeholder="Pilih tahun">
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Step 3: Pilihan Program -->
                    <div class="form-step" id="step3">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 3: Pilihan Program Studi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label for="gelombang" class="block text-sm font-medium text-gray-600 mb-1">Gelombang *</label>
                                <select id="gelombang" name="gelombang_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white" required>
                                    <option value="" selected disabled>-- Pilih Gelombang --</option>
                                    @foreach($gelombangs as $g)
                                        <option value="{{ $g->id }}" {{ old('gelombang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }} ({{ $g->tanggal_buka }} - {{ $g->tanggal_tutup }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="jalur" class="block text-sm font-medium text-gray-600 mb-1">Jalur *</label>
                                <select id="jalur" name="jalur_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition bg-white" required>
                                    <option value="" selected disabled>-- Pilih Jalur --</option>
                                    @foreach($jalurs as $j)
                                        <option value="{{ $j->id }}" {{ old('jalur_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Upload Dokumen -->
                    <div class="form-step" id="step4">
                        <h4 class="text-xl font-semibold mb-6 text-gray-800">Langkah 4: Upload Dokumen</h4>
                        <p class="text-gray-500 mb-6 text-sm">Silakan unggah dokumen dalam format PDF atau JPG (Maks. 2MB).</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pas Foto -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Pas Foto 3x4 (berwarna)</label>
                                <label for="pasFoto" class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="pasFoto-label">Klik untuk unggah file</span>
                                </label>
                                <input type="file" id="pasFoto" name="pas_foto" class="filepond" accept="image/jpeg,image/png" required>
                            </div>

                            <!-- Ijazah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Ijazah / SKL</label>
                                <label for="ijazah" class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="ijazah-label">Klik untuk unggah file</span>
                                </label>
                                <input type="file" id="ijazah" name="ijazah" class="filepond" accept="application/pdf,image/jpeg,image/png" required>
                            </div>

                            <!-- Akta -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Akta Kelahiran</label>
                                <label for="aktaKelahiran" class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="aktaKelahiran-label">Klik untuk unggah file</span>
                                </label>
                                <input type="file" id="aktaKelahiran" name="akta" class="filepond" accept="application/pdf,image/jpeg,image/png" required>
                            </div>

                            <!-- Rapor -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Rapor</label>
                                <label for="rapor" class="file-upload-label flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-hijau-terang transition">
                                    <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
                                    <span class="text-sm text-gray-500 mt-1" id="rapor-label">Klik untuk unggah file</span>
                                </label>
                                <input type="file" id="rapor" name="rapor" class="filepond" accept="application/pdf" required>
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
                                        <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700 w-1/3">Nama Lengkap</th>
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
                                        <th class="py-3 px-4 bg-gray-50 font-semibold text-gray-700">Jurusan Pilihan</th>
                                        <td class="py-3 px-4" id="reviewPilihan"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-start gap-3">
                            <input id="agreeTerms" type="checkbox" class="w-5 h-5 text-hijau-utama border-gray-300 rounded focus:ring-hijau-utama cursor-pointer">
                            <label for="agreeTerms" class="text-sm text-gray-600 cursor-pointer">
                                Saya menyatakan bahwa data yang diisi adalah benar dan bersedia mematuhi semua peraturan pendaftaran. *
                            </label>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8">
                        <button type="button" id="prevBtn" class="hidden bg-gray-500 hover:bg-gray-600 text-white font-medium py-2.5 px-6 rounded-lg transition duration-300">
                            Sebelumnya
                        </button>
                        <button type="button" id="nextBtn" class="ml-auto bg-hijau-utama hover:bg-hijau-gelap text-white font-medium py-2.5 px-6 rounded-lg transition duration-300">
                            Selanjutnya
                        </button>
                        <noscript>
                            <button type="submit" class="ml-auto bg-hijau-gelap hover:bg-green-900 text-white font-medium py-3 px-8 rounded-lg text-lg transition duration-300">Kirim Pendaftaran</button>
                        </noscript>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Success Modal (Pure Tailwind) -->
    <div id="successModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-8 text-center transform transition-all">
            <div class="text-green-500 mb-4">
                <i class="bi bi-check-circle-fill text-6xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Pendaftaran Berhasil!</h3>
            <p class="text-gray-600 mb-6">Terima kasih, data Anda telah kami terima. Silakan cek no WhatsApp Anda secara berkala untuk informasi selanjutnya.</p>
            <button id="closeModalBtn" class="bg-hijau-utama hover:bg-hijau-gelap text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                Tutup
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Minimal step navigation with persistence (localStorage)
        (function(){
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const steps = Array.from(document.querySelectorAll('.form-step'));
            const progressSteps = Array.from(document.querySelectorAll('.progress-step'));
            const form = document.getElementById('regForm');
            let current = parseInt(localStorage.getItem('pendaftaran_step')) || 1;

            function show(i){
                steps.forEach((s, idx) => s.classList.toggle('active', idx+1===i));
                progressSteps.forEach((p, idx) => {
                    const icon = p.querySelector('.step-icon');
                    const text = p.querySelector('p');
                    icon.className = 'step-icon w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white font-semibold transition-all duration-400 ' + (idx+1< i ? 'bg-hijau-gelap' : (idx+1===i ? 'bg-hijau-utama' : 'bg-gray-300'));
                    text.className = (idx+1< i ? 'mt-2 text-xs md:text-sm font-medium text-hijau-gelap transition-all duration-400' : (idx+1===i ? 'mt-2 text-xs md:text-sm font-medium text-hijau-utama transition-all duration-400' : 'mt-2 text-xs md:text-sm font-medium text-gray-400 transition-all duration-400'));
                });
                prevBtn.classList.toggle('hidden', i===1);
                nextBtn.classList.toggle('hidden', i===steps.length);
                localStorage.setItem('pendaftaran_step', i);
            }

            function saveFormData(){
                if(!form) return;
                const fd = new FormData(form);
                const data = {};
                fd.forEach((v,k)=>{
                    const el = form.querySelector(`[name="${k}"]`);
                    if(!el) return;
                    if(el.type === 'file') return; // cannot persist files
                    if(el.type === 'checkbox') data[k] = el.checked;
                    else data[k] = v;
                });
                try{ localStorage.setItem('pendaftaran_data', JSON.stringify(data)); } catch(e){}
            }

            function restoreFormData(){
                if(!form) return;
                const raw = localStorage.getItem('pendaftaran_data');
                if(!raw) return;
                try{
                    const data = JSON.parse(raw);
                    Object.keys(data).forEach(k => {
                        const el = form.querySelector(`[name="${k}"]`);
                        if(!el) return;
                        if(el.type === 'checkbox') el.checked = !!data[k];
                        else el.value = data[k];
                    });
                } catch(e){}
            }

            if(form){
                form.addEventListener('input', saveFormData);
                form.addEventListener('change', saveFormData);
                form.addEventListener('submit', function(){
                    localStorage.removeItem('pendaftaran_data');
                    localStorage.removeItem('pendaftaran_step');
                });
            }

            function validateCurrentStep(){
                const currentStepElement = steps[current-1];
                const inputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;

                inputs.forEach(input => {
                    if (input.type === 'checkbox') {
                        if (!input.checked) {
                            input.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    } else if (input.type === 'file') {
                        let hasFile = false;
                        if (window.FilePond && FilePond.find) {
                            const pond = FilePond.find(input);
                            if (pond) hasFile = pond.getFiles().length > 0;
                            else hasFile = input.files && input.files.length > 0;
                        } else {
                            hasFile = input.files && input.files.length > 0;
                        }

                        const labelEl = document.getElementById(input.id + '-label');
                        if (!hasFile) {
                            if (labelEl) labelEl.innerHTML = `<span class="text-red-500">File wajib diunggah!</span>`;
                            input.closest('.file-upload-label')?.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            if (labelEl) labelEl.innerHTML = 'File terpilih';
                            input.closest('.file-upload-label')?.classList.remove('border-red-500');
                        }
                    } else {
                        if (!input.value || (input.tagName === 'SELECT' && input.value === "")) {
                            input.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                            isValid = false;
                        } else {
                            input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                        }
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Harap lengkapi semua field yang wajib diisi.',
                        confirmButtonColor: '#16a34a',
                    showClass: {
                        popup: `
                            animate__animated
                            animate__tada
                        `
                    },
                    hideClass: {
                        popup: `
                            animate__animated
                            animate__bounceOut
                        `
                    },
                    });
                }
                return isValid;
            }

            if(nextBtn) nextBtn.addEventListener('click', ()=>{
                // validate current step before moving forward
                if (current < steps.length && !validateCurrentStep()) return;
                current = Math.min(steps.length, current+1);
                show(current);
                saveFormData();
                window.scrollTo(0,0);
            });
            if(prevBtn) prevBtn.addEventListener('click', ()=>{ current = Math.max(1, current-1); show(current); saveFormData(); window.scrollTo(0,0); });

            restoreFormData();
            show(current);
        })();

        // Flatpickr year-only + FilePond init
        document.addEventListener('DOMContentLoaded', function(){
            // Flatpickr year picker using monthSelect plugin to pick year
            if (window.flatpickr && window.monthSelectPlugin) {
                flatpickr('.flatpickr-year', {
                    plugins: [new monthSelectPlugin({ shorthand: true, dateFormat: 'Y', altFormat: 'Y'})],
                    altInput: true,
                    altFormat: 'Y',
                    dateFormat: 'Y',
                    defaultDate: '{{ old('tahun_lulus') ?? '' }}',
                    maxDate: 'today',
                });

                flatpickr('#tanggalLahir', {
                    dateFormat: 'Y-m-d',
                    defaultDate: '{{ old('tanggal_lahir') ?? '' }}',
                });
            }

            // FilePond init (UI only, no auto upload)
            if (window.FilePond) {
                FilePond.setOptions({ server: null, allowProcess: false });
                FilePond.parse(document.body);
            }
        });
    </script>
</body>
</html>

