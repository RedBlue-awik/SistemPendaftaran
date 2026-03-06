<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - SPMB SMK Mamba'ul Ihsan</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'hijau-utama': '#2E8B57',
                        'hijau-gelap': '#1F5F3F',
                        'hijau-terang': '#E8F5E9',
                        'hijau-pudar': '#C8E6C9',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-poppins">

    <div
        class="relative w-full max-w-5xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden flex min-h-[600px] md:min-h-[700px]">

        <!-- Left Side: Info / Branding (Hidden on small screens) -->
        <div
            class="hidden md:flex md:w-1/2 bg-hijau-gelap text-white p-10 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Background -->
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-hijau-utama rounded-full filter blur-3xl opacity-30 transform translate-x-20 -translate-y-20">
            </div>
            <div
                class="absolute bottom-0 left-0 w-64 h-64 bg-hijau-pudar rounded-full filter blur-3xl opacity-20 transform -translate-x-20 translate-y-20">
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <img src="{{ asset('logo.png') }}" alt="Logo SPMB" class="w-16 h-16 mb-3">
                    <div>
                        <h2 class="text-xl font-bold leading-tight">SMK MAMBA'UL IHSAN</h2>
                        <p class="text-xs text-hijau-terang opacity-80">Sistem Penerimaan Murid Baru</p>
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-4 leading-snug">Bergabunglah Bersama Kami</h1>
                <p class="text-sm text-gray-200 opacity-90">
                    Daftarkan diri Anda untuk menjadi bagian dari SMK Mamba'ul Ihsan yang berprestasi dan berkarakter.
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 relative flex flex-col justify-center">

            <div class="md:hidden text-center mb-8">
                <img src="{{ asset('logo.png') }}" alt="Logo SPMB" class="mx-auto w-16 h-16 mb-4">
                <h2 class="text-xl font-bold text-hijau-gelap mt-2">SPMB SMK MAMBA'UL IHSAN</h2>
            </div>

            <div class="w-full">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h3>
                <p class="text-gray-500 text-sm mb-6">Daftarkan diri Anda untuk memulai pendaftaran.</p>

                <!-- Taruh ini tepat di atas tag <form> -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="name" placeholder="Nama Anda"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                required value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email Aktif*</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                required value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">No WhatsApp Aktif*</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="bi bi-phone"></i>
                            </span>
                            <input type="phone" name="phone" placeholder="08123456789"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                required value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input id="password" type="password" name="password" placeholder="Min. 8 karakter"
                                class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-utama focus:border-transparent transition"
                                required>
                            <i id="togglePassword"
                                class="bi bi-eye-slash absolute inset-y-0 right-0 flex items-center pr-3 text-xl text-gray-500 cursor-pointer"
                                role="button" aria-label="Tampilkan password"></i>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-hijau-utama hover:bg-hijau-gelap text-white font-semibold py-3 rounded-lg transition duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <span>Daftar Sekarang</span>
                        <i class="bi bi-person-plus"></i>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-hijau-utama hover:underline">Masuk di
                            sini</a>
                    </p>
                </div>
            </div>

            <!-- Back to Home Link -->
            <div class="mt-8 text-center">
                <a href="{{ url('/') }}"
                    class="text-sm text-gray-500 hover:text-hijau-utama transition flex items-center justify-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pwd = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');
            if (pwd && toggle) {
                toggle.addEventListener('click', function() {
                    if (pwd.type === 'password') {
                        pwd.type = 'text';
                        toggle.classList.remove('bi-eye-slash');
                        toggle.classList.add('bi-eye');
                        toggle.setAttribute('aria-label', 'Sembunyikan password');
                    } else {
                        pwd.type = 'password';
                        toggle.classList.remove('bi-eye');
                        toggle.classList.add('bi-eye-slash');
                        toggle.setAttribute('aria-label', 'Tampilkan password');
                    }
                });
            }
        });
    </script>
    <script>
        const ToastS = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1300,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        
        const ToastE = Swal.mixin({
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

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                ToastS.fire({ icon: 'success', title: "{{ session('success') }}" });
            @endif

            @if(session('error'))
                ToastE.fire({ icon: 'error', title: "{{ session('error') }}" });
            @endif

            @if ($errors->any())
                let msgs = "";
                @foreach ($errors->all() as $err)
                    msgs += "{{ $err }} ";
                @endforeach
                ToastE.fire({ icon: 'error', title: msgs });
            @endif
        });
    </script>
</body>

</html>
