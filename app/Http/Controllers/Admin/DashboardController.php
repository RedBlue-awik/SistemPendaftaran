<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Gelombang;
use App\Models\Jalur;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalPendaftar = Pendaftaran::count();

        $today = Carbon::today();
        $activeGelombang = Gelombang::whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->first();

        $totalGelombangAktif = $activeGelombang ? 1 : 0;
        $glombangAktif = Gelombang::where('status', 'aktif')->first();
        $totalJalur = Jalur::count();
        $totalUsers = User::count();

        // Jalur distribution with pendaftar count
        $jalurs = Jalur::withCount('pendaftarans')->get()->map(function ($j) {
            return [
                'id' => $j->id,
                'nama' => $j->nama_jalur ?? $j->nama ?? '—',
                'pendaftar' => $j->pendaftarans_count ?? 0,
                'kode' => strtoupper(substr(preg_replace('/[^A-Z0-9]/i', '', ($j->nama_jalur ?? $j->nama ?? '')), 0, 3)),
            ];
        });

        // Chart: last 7 months pendaftaran counts
        $months = collect();
        for ($i = 6; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $months->push($m->format('Y-m'));
        }

        $counts = Pendaftaran::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, count(*) as c")
            ->where('created_at', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy('ym')
            ->pluck('c', 'ym')
            ->toArray();

        $chartData = $months->map(fn($ym) => isset($counts[$ym]) ? (int) $counts[$ym] : 0)->toArray();

        $recent = Pendaftaran::latest()->take(5)->get(['nomor_pendaftaran', 'nama_lengkap', 'status_pendaftaran']);

        return view('admin.dashboard', compact(
            'totalPendaftar', 'totalGelombangAktif', 'totalJalur', 'totalUsers',
            'jalurs', 'chartData', 'recent'
        ));
    }
}
