<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use App\Models\ResepItem;
use App\Models\ResepRacikan;
use App\Models\ResepRacikanItem;
use App\Models\ObatalkesM;
use App\Models\SignaM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResepController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isPasien()) {
            // Pasien hanya melihat resep miliknya
            $reseps = Resep::with('user')
                          ->where('user_id', $user->id)
                          ->latest()
                          ->paginate(10);
        } elseif ($user->isApoteker()) {
            // Apoteker hanya melihat resep dengan status pending
            $reseps = Resep::with('user')
                          ->where('status', 'pending')
                          ->latest()
                     ->paginate(10);
        } else {
            // Admin dan dokter melihat semua resep
            $reseps = Resep::with('user')->latest()->paginate(10);
        }

        return view('resep.index', compact('reseps'));
    }

    public function create()
    {
        $obatalkes = ObatalkesM::where('is_deleted', false)->where('stok', '>', 0)->get();
        $signa = SignaM::where('is_deleted', false)->get();
        return view('resep.create', compact('obatalkes', 'signa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.obatalkes_id' => 'required|exists:obatalkes_m,id',
            'items.*.signa_m_id' => 'required|exists:signa_m,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.aturan_pakai' => 'required|string',
            'racikan' => 'array',
            'racikan.*.nama_racikan' => 'required_with:racikan|string',
            'racikan.*.signa_m_id' => 'required_with:racikan|exists:signa_m,id',
            'racikan.*.qty' => 'required_with:racikan|integer|min:1',
            'racikan.*.aturan_pakai' => 'required_with:racikan|string',
            'racikan.*.items' => 'array',
            'racikan.*.items.*.obatalkes_id' => 'required|exists:obatalkes_m,id',
            'racikan.*.items.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $resep = Resep::create([
                'user_id' => Auth::id(),
                'nama_pasien' => $request->nama_pasien,
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
                'status' => 'pending',
            ]);
            // Non racikan
            foreach ($request->items as $item) {
                $obat = ObatalkesM::find($item['obatalkes_id']);
                if ($obat->stok < $item['qty']) {
                    abort(400, 'Stok obat tidak cukup');
                }
                $obat->decrement('stok', $item['qty']);
                ResepItem::create([
                    'resep_id' => $resep->id,
                    'obatalkes_id' => $item['obatalkes_id'],
                    'signa_m_id' => $item['signa_m_id'],
                    'qty' => $item['qty'],
                    'aturan_pakai' => $item['aturan_pakai'],
                ]);
            }
            // Racikan
            if ($request->racikan) {
                foreach ($request->racikan as $racik) {
                    $racikan = ResepRacikan::create([
                        'resep_id' => $resep->id,
                        'nama_racikan' => $racik['nama_racikan'],
                        'signa_m_id' => $racik['signa_m_id'],
                        'qty' => $racik['qty'],
                        'aturan_pakai' => $racik['aturan_pakai'],
                    ]);
                    foreach ($racik['items'] as $rItem) {
                        $obat = ObatalkesM::find($rItem['obatalkes_id']);
                        if ($obat->stok < $rItem['qty']) {
                            abort(400, 'Stok obat racikan tidak cukup');
                        }
                        $obat->decrement('stok', $rItem['qty']);
                        ResepRacikanItem::create([
                            'racikan_id' => $racikan->id,
                            'obatalkes_id' => $rItem['obatalkes_id'],
                            'qty' => $rItem['qty'],
                        ]);
                    }
                }
            }
        });
        return redirect()->route('resep.index')->with('success', 'Resep berhasil disimpan.');
    }

    public function show(Resep $resep)
    {
        $resep->load(['user', 'items.obatalkes', 'items.signa', 'racikan.racikanItems.obatalkes', 'racikan.signa']);
        return view('resep.show', compact('resep'));
    }

    public function update(Request $request, Resep $resep)
    {
        $user = Auth::user();
        
        if (!$user->isApoteker()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|in:diproses,selesai'
        ]);

            $resep->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'selesai' ? now() : null
        ]);
        
        return redirect()->route('resep.index')->with('success', 'Status resep berhasil diupdate.');
    }

    public function destroy(Resep $resep)
    {
            $resep->delete();
            return redirect()->route('resep.index')->with('success', 'Resep berhasil dihapus.');
    }

    public function pdf(Resep $resep)
    {
        $resep->load(['user', 'items.obatalkes', 'items.signa', 'racikan.racikanItems.obatalkes', 'racikan.signa']);
        $pdf = Pdf::loadView('resep.pdf', compact('resep'));
        return $pdf->download('resep-'.$resep->id.'.pdf');
    }

    public function approve(Resep $resep)
    {
        $user = Auth::user();
        
        if (!$user->canApprovePrescription()) {
            abort(403, 'Unauthorized');
        }

            $resep->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now()
            ]);

        return redirect()->route('resep.index')->with('success', 'Resep berhasil disetujui.');
    }

    public function reject(Resep $resep)
    {
        $user = Auth::user();
        
        if (!$user->canApprovePrescription()) {
            abort(403, 'Unauthorized');
        }
        
            $resep->update([
                'status' => 'rejected',
                'rejected_by' => $user->id,
            'rejected_at' => now()
        ]);
        
        return redirect()->route('resep.index')->with('success', 'Resep berhasil ditolak.');
    }

    public function receive(Resep $resep)
    {
        $user = Auth::user();
        
        if (!$user->canReceivePrescription()) {
            abort(403, 'Unauthorized');
        }
        
            $resep->update([
            'status' => 'processing',
                'received_by' => $user->id,
                'received_at' => now()
            ]);

        return redirect()->route('resep.index')->with('success', 'Resep berhasil diterima untuk diproses.');
    }

    public function complete(Resep $resep)
    {
        $user = Auth::user();
        
        if (!$user->canReceivePrescription()) {
            abort(403, 'Unauthorized');
        }
        
        $resep->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        return redirect()->route('resep.index')->with('success', 'Resep berhasil diselesaikan.');
    }

    public function processing()
    {
        $user = Auth::user();
        
        if (!$user->isApoteker()) {
            abort(403, 'Unauthorized');
        }
        
        $reseps = Resep::with('user')
                      ->where('status', 'diproses')
                      ->latest()
                      ->paginate(10);
        
        return view('resep.processing', compact('reseps'));
    }

    public function completed()
    {
        $user = Auth::user();
        
        if (!$user->isApoteker()) {
            abort(403, 'Unauthorized');
        }
        
        $reseps = Resep::with('user')
                      ->where('status', 'selesai')
                      ->latest()
                      ->paginate(10);
        
        return view('resep.completed', compact('reseps'));
    }
} 