@extends('layouts.app')

@section('title', 'Dashboard')
@section('pageTitle', 'Dashboard')

@section('content')
    @php
        $glombangAktif = \App\Models\Gelombang::where('status', 'aktif')->first();

        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $chartLabels[] = \Carbon\Carbon::now()->subMonths($i)->format('Y-m');
        }
    @endphp

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <!-- Total Pendaftar -->
        <div class="card-hover bg-white border border-border rounded-2xl p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-muted mb-1">Total Pendaftar</p>
                    <h3 class="text-3xl font-bold text-text-main mb-2" id="totalPendaftar">{{ $totalPendaftar }}</h3>
                    <div class="flex items-center gap-1 text-accent text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-green-100">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Gelombang Aktif -->
        <div class="card-hover bg-white border border-border rounded-2xl p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-muted mb-1">Gelombang Aktif</p>
                    <h3 class="text-3xl font-bold text-text-main mb-2">{{ $totalGelombangAktif }}</h3>
                    <div class="flex items-center gap-1 text-warning text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $glombangAktif->nama ?? 'Tidak Ada' }}</span>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-amber-100">
                    <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Jalur Tersedia -->
        <div class="card-hover bg-white border border-border rounded-2xl p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-muted mb-1">Jalur Tersedia</p>
                    <h3 class="text-3xl font-bold text-text-main mb-2">{{ $totalJalur }}</h3>
                    <div class="flex items-center gap-1 text-muted text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-sky-100">
                    <svg class="w-6 h-6 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts dan Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white border border-border rounded-2xl p-6">
            <h3 class="text-lg font-bold text-text-main mb-6">Statistik Pendaftaran (7 bulan terakhir)</h3>
            <div class="flex items-end gap-4 h-48">
                @php
                    $max = max($chartData) ?: 1;
                @endphp
                @foreach ($chartData as $i => $val)
                    @php
                        $height = (int) max(10, ($val / $max) * 100);
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <span class="text-sm text-muted">{{ $val }}</span>
                        <div class="w-full bg-green-50 rounded-t-lg overflow-hidden flex-1 flex items-end">
                            <div class="w-full bg-gradient-to-t from-accent to-accent-light rounded-t-lg transition-all"
                                style="height: {{ $height }}%"></div>
                        </div>
                        <small class="text-xs text-muted">
                            {{ \Carbon\Carbon::parse($chartLabels[$i])->format('M Y') }}
                        </small>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Distribusi Jalur -->
        <div class="bg-white border border-border rounded-2xl p-6">
            <h3 class="text-lg font-bold text-text-main mb-6">Distribusi Jalur</h3>
            <div class="space-y-4">
                @php
                    $totalPendaftarJalur = $jalurs->sum('pendaftar') ?: 1;
                    $colors = ['bg-accent', 'bg-sky-500', 'bg-amber-500', 'bg-purple-500'];
                @endphp
                @foreach ($jalurs as $i => $jalur)
                    @php
                        $percent = (int) round(($jalur['pendaftar'] / $totalPendaftarJalur) * 100);
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-text-main font-medium">{{ $jalur['nama'] }}</span>
                            <span class="text-muted">{{ $percent }}%</span>
                        </div>
                        <div class="h-2 bg-green-50 rounded-full overflow-hidden">
                            <div class="h-full {{ $colors[$i % count($colors)] }} rounded-full"
                                style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Table -->
    <div class="bg-white border border-border rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-border">
            <h3 class="text-lg font-bold text-text-main">Pendaftar Terbaru</h3>
            <a href="{{ route('admin.pendaftars.index') }}"
                class="btn-primary px-4 py-2 rounded-xl text-sm font-medium text-white">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-green-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">No. Pendaftaran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    {{-- PERBAIKAN: Gunakan $recent, bukan $recentPendaftars --}}
                    @foreach ($recent as $p)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm text-text-main font-mono">
                                {{ $p->nomor_pendaftaran ?? '#' . $p->id }}</td>
                            <td class="px-6 py-4 text-sm text-text-main">{{ $p->nama_lengkap ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if ($p->status_pendaftaran === 'Diterima')
                                    <span class="badge bg-green-100 text-green-700">Diterima</span>
                                @elseif($p->status_pendaftaran === 'Ditolak')
                                    <span class="badge bg-red-100 text-red-700">Ditolak</span>
                                @else
                                    <span
                                        class="badge bg-amber-100 text-amber-700">{{ $p->status_pendaftaran ?? 'Proses' }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const counterEl = document.getElementById('totalPendaftar');

            if (counterEl) {
                const target = parseInt(counterEl.innerText);

                // Fungsi animasi counter
                const animateCounter = (element, start, end, duration) => {
                    let startTimestamp = null;
                    const step = (timestamp) => {
                        if (!startTimestamp) startTimestamp = timestamp;
                        const progress = Math.min((timestamp - startTimestamp) / duration, 1);

                        // Efek memperlambat di akhir (Ease Out Cubic)
                        const easeProgress = 1 - Math.pow(1 - progress, 3);

                        const current = Math.floor(easeProgress * (end - start) + start);

                        // Format angka dengan titik (contoh: 1.500)
                        element.innerText = current.toLocaleString('id-ID');

                        if (progress < 1) {
                            window.requestAnimationFrame(step);
                        } else {
                            // Pastikan angka akhir tepat
                            element.innerText = end.toLocaleString('id-ID');
                        }
                    };
                    window.requestAnimationFrame(step);
                };

                // Mulai animasi: dari 0 ke target, selama 2000ms (2 detik)
                animateCounter(counterEl, 0, target, 2000);
            }
        });
    </script>
@endpush
