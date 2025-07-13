<?php

namespace App\Http\Controllers;

use App\Models\ObatalkesM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObatalkesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $obatalkes = ObatalkesM::where('is_deleted', false)->paginate(10);
        return view('obatalkes.index', compact('obatalkes'));
    }
    
    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('obatalkes.create');
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'obatalkes_kode' => 'required|string|max:100',
            'obatalkes_nama' => 'required|string|max:250',
            'stok' => 'required|numeric|min:0',
        ]);
        ObatalkesM::create($validated);
        return redirect()->route('obatalkes.index')->with('success', 'Obat berhasil ditambahkan.');
    }
    
    public function show(ObatalkesM $obatalkes)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('obatalkes.show', compact('obatalkes'));
    }
    
    public function edit(ObatalkesM $obatalkes)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('obatalkes.edit', compact('obatalkes'));
    }
    
    public function update(Request $request, ObatalkesM $obatalkes)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'obatalkes_kode' => 'required|string|max:100',
            'obatalkes_nama' => 'required|string|max:250',
            'stok' => 'required|numeric|min:0',
        ]);
        $obatalkes->update($validated);
        return redirect()->route('obatalkes.index')->with('success', 'Obat berhasil diupdate.');
    }
    
    public function destroy(ObatalkesM $obatalkes)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $obatalkes->update(['is_deleted' => true]);
        return redirect()->route('obatalkes.index')->with('success', 'Obat berhasil dihapus.');
    }
} 