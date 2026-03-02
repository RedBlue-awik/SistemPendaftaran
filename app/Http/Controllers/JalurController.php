<?php

namespace App\Http\Controllers;

use App\Models\Jalur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JalurController extends Controller
{
    public function index()
    {
        $jalurs = Jalur::all();
        return view('jalurs.index', compact('jalurs'));
    }

    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $jalurs = Jalur::orderBy('created_at', 'desc')->get();
        return view('admin.jalurs', compact('jalurs'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'nama_jalur' => 'required|string',
            'deskripsi' => 'nullable|string',
            'batas_pendaftaran' => 'required|integer',
        ]);
        Jalur::create($data);
        return back()->with('success', 'Jalur dibuat');
    }

    public function destroy(Jalur $jalur)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $jalur->delete();
        return back()->with('success', 'Jalur dihapus');
    }
}
