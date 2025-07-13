<?php

namespace App\Http\Controllers;

use App\Models\SignaM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $signa = SignaM::where('is_deleted', false)->paginate(10);
        return view('signa.index', compact('signa'));
    }
    
    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('signa.create');
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'signa_kode' => 'required|string|max:100',
            'signa_nama' => 'required|string|max:250',
        ]);
        SignaM::create($validated);
        return redirect()->route('signa.index')->with('success', 'Signa berhasil ditambahkan.');
    }
    
    public function show(SignaM $signa)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('signa.show', compact('signa'));
    }
    
    public function edit(SignaM $signa)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('signa.edit', compact('signa'));
    }
    
    public function update(Request $request, SignaM $signa)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'signa_kode' => 'required|string|max:100',
            'signa_nama' => 'required|string|max:250',
        ]);
        $signa->update($validated);
        return redirect()->route('signa.index')->with('success', 'Signa berhasil diupdate.');
    }
    
    public function destroy(SignaM $signa)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'dokter', 'apoteker'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $signa->update(['is_deleted' => true]);
        return redirect()->route('signa.index')->with('success', 'Signa berhasil dihapus.');
    }
} 