<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GelombangController extends Controller
{
    public function index()
    {
        // show active gelombang(s)
        $today = date('Y-m-d');
        $gelombangs = Gelombang::where('status', 'aktif')->where('tanggal_mulai', '<=', $today)->where('tanggal_selesai', '>=', $today)->get();

        return view('gelombangs.index', compact('gelombangs'));
    }

    // admin CRUD
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $gelombangs = Gelombang::orderBy('tanggal_mulai', 'desc')->get();
        return view('admin.gelombangs', compact('gelombangs'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $data = $request->validate([
            'nama' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'batas_pendaftaran' => 'required|integer',
        ]);
        $today = Carbon::today();

        if ($today->lt($data['tanggal_mulai'])) {
            $data['status'] = 'segera';
        } elseif ($today->between($data['tanggal_mulai'], $data['tanggal_selesai'])) {
            $data['status'] = 'aktif';
        } else {
            $data['status'] = 'tutup';
        }

        Gelombang::create($data);
        return back()->with('success', 'Gelombang berhasil dibuat');
    }

    public function destroy(Gelombang $gelombang)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $gelombang->delete();
        return back()->with('success', 'Gelombang berhasil dihapus');
    }

    public function update(Request $request, Gelombang $gelombang)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $data = $request->validate([
            'nama' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'batas_pendaftaran' => 'required|integer',
        ]);
        $gelombang->update($data);
        return back()->with('success', 'Gelombang berhasil diperbarui');
    }
}
