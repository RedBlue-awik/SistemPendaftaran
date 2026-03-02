<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin SPMB - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'display': ['Space Grotesk', 'sans-serif'], 'body': ['Inter', 'sans-serif'] },
                    colors: {
                        bg: '#f0fdf4', surface: '#ffffff', card: '#ffffff', border: '#bbf7d0',
                        muted: '#15803d', accent: '#16a34a', 'accent-light': '#22c55e',
                        'accent-dark': '#15803d', warning: '#f59e0b', danger: '#ef4444',
                        'text-main': '#14532d', 'text-secondary': '#166534',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Space Grotesk', sans-serif; }
        body { background: #f0fdf4; color: #14532d; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #dcfce7; }
        ::-webkit-scrollbar-thumb { background: #86efac; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #4ade80; }
        .gradient-mesh {
            background: radial-gradient(ellipse at 20% 0%, rgba(134, 239, 172, 0.4) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 100%, rgba(74, 222, 128, 0.2) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 50%, rgba(167, 243, 208, 0.3) 0%, transparent 70%);
        }
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05); border-color: #4ade80; }
        .menu-item { position: relative; transition: all 0.2s ease; }
        .menu-item::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 0; background: #16a34a; border-radius: 0 2px 2px 0; transition: height 0.2s ease;
        }
        .menu-item:hover::before, .menu-item.active::before { height: 60%; }
        .menu-item.active { background: rgba(167, 243, 208, 0.5); color: #14532d; font-weight: 600; }
        .menu-item:hover { background: rgba(167, 243, 208, 0.3); }
        .table-row { transition: background 0.15s ease; }
        .table-row:hover { background: rgba(167, 243, 208, 0.3); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease forwards; }
        .delay-100 { animation-delay: 0.1s; } .delay-200 { animation-delay: 0.2s; } .delay-300 { animation-delay: 0.3s; } .delay-400 { animation-delay: 0.4s; }
        .btn-primary { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); transition: all 0.2s ease; color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3); }
        .badge { font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600; }
        .sidebar-overlay { background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(4px); }
        input, select, textarea { background-color: #f0fdf4 !important; border-color: #bbf7d0 !important; color: #14532d !important; }
        input::placeholder { color: #166534 !important; opacity: 0.7; }
        input:focus, select:focus, textarea:focus { border-color: #22c55e !important; box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2) !important; }
    </style>
</head>
<body class="min-h-screen text-text-main">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-surface border-r border-border z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-lg">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-border">
                <div class="flex items-center gap-3">
                        <img src="{{ asset('logo.png') }}" alt="Logo Smk Mambaul Ihsan" class="w-12 h-12">
                    <div>
                        <h1 class="text-lg font-bold text-text-main">SPMB</h1>
                        <p class="text-xs text-muted">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-text-secondary hover:text-text-main">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="font-medium">User</span>
                </a>
                <a href="{{ route('admin.gelombangs.index') }}" class="menu-item {{ request()->routeIs('admin.gelombangs.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-text-secondary hover:text-text-main">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="font-medium">Gelombang</span>
                </a>
                <a href="{{ route('admin.pendaftars.index') }}" class="menu-item {{ request()->routeIs('admin.pendaftars.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-text-secondary hover:text-text-main">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="font-medium">Pendaftar</span>
                </a>
                <a href="{{ route('admin.jalurs.index') }}" class="menu-item {{ request()->routeIs('admin.jalurs.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-text-secondary hover:text-text-main">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <span class="font-medium">Jalur</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-border">
                <div class="flex items-center gap-3 rounded-lg bg-red-50">
                    <a href="{{ route('logout') }}" class="p-3 w-100 rounded-lg hover:bg-red-100 transition-colors" title="Logout">
                        <span class="text-red-500 mx-2 text-md font-semibold">Logout</span>
                        <i class="fas fa-right-from-bracket text-md text-red-500"></i>
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen gradient-mesh">
        <!-- Header -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-border shadow-sm">
            <div class="flex items-center justify-between px-4 lg:px-8 py-4">
                <div class="flex items-center gap-4">
                    <button id="menuToggle" class="lg:hidden p-2 rounded-lg bg-green-50 hover:bg-green-100 transition-colors" onclick="toggleSidebar()">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="hidden sm:block">
                        <h2 class="text-xl font-bold text-text-main">@yield('pageTitle', 'Dashboard')</h2>
                        <p class="text-sm text-muted">Selamat datang kembali, {{ auth()->user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-text-main truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-muted truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div id="pageContent" class="p-4 lg:p-8">
            @yield('content')
        </div>
    </main>

    <!-- Global Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        function getStatusBadge(status) {
            const styles = {
                'Aktif': 'bg-green-100 text-green-700', 'Nonaktif': 'bg-red-100 text-red-700',
                'Diterima': 'bg-green-100 text-green-700', 'Ditolak': 'bg-red-100 text-red-700',
                'Proses': 'bg-amber-100 text-amber-700', 'Akan Datang': 'bg-sky-100 text-sky-700',
            };
            return `<span class="badge ${styles[status] || 'bg-gray-100 text-gray-700'}">${status}</span>`;
        }
    </script>
    @stack('scripts')
</body>
</html>