<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use App\Models\ResepItem;
use App\Models\ResepRacikan;
use App\Models\ResepRacikanItem;
use App\Models\ObatalkesM;
use App\Models\SignaM;
use App\Models\Apotek;
use App\Services\WhatsAppService;
use App\Services\NotificationService;
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
            // Pasien hanya melihat resep miliknya sendiri
            $reseps = Resep::with(['user', 'apotek'])
                          ->where('user_id', $user->id)
                          ->latest()
                          ->paginate(10);
        } elseif ($user->isApoteker() || $user->isFarmasi()) {
            // Apoteker/Farmasi hanya melihat resep di apoteknya dengan status pending
            $reseps = Resep::with(['user', 'apotek'])
                          ->where('apotek_id', $user->apotek_id)
                          ->where('status', 'pending')
                          ->latest()
                     ->paginate(10);
        } else {
            // Admin dan dokter melihat semua resep
            $reseps = Resep::with(['user', 'apotek'])->latest()->paginate(10);
        }

        return view('resep.index', compact('reseps'));
    }

    public function create()
    {
        $obatalkes = ObatalkesM::where('is_deleted', false)->where('stok', '>', 0)->get();
        $signa = SignaM::where('is_deleted', false)->get();
        $apotek = Apotek::active()->get();
        $user = Auth::user();
        return view('resep.create', compact('obatalkes', 'signa', 'apotek', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'apotek_id' => 'required|exists:apotek,id',
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

        // Validasi nama pasien harus sesuai dengan user yang login
        if ($request->nama_pasien !== $user->name) {
            return back()->withErrors(['nama_pasien' => 'Nama pasien harus sesuai dengan user yang login.'])->withInput();
        }

        DB::transaction(function () use ($request) {
            // Generate nomor antrian
            $today = now()->toDateString();
            $apotekId = $request->apotek_id;
            $apotek = Apotek::find($apotekId);
            $kodeApotek = $apotek ? strtoupper(substr(preg_replace('/[^A-Z]/i', '', $apotek->nama_apotek), 0, 1)) : 'A';
            $lastResep = Resep::where('apotek_id', $apotekId)
                ->where('tgl_pengajuan', $today)
                ->orderByDesc('no_antrian')
                ->first();
            if ($lastResep && preg_match('/(\d{3})$/', $lastResep->no_antrian, $m)) {
                $nextNo = intval($m[1]) + 1;
            } else {
                $nextNo = 1;
            }
            $noAntrian = $kodeApotek . '-' . str_pad($nextNo, 3, '0', STR_PAD_LEFT);

            $resep = Resep::create([
                'user_id' => Auth::id(),
                'apotek_id' => $request->apotek_id,
                'no_antrian' => $noAntrian,
                'nama_pasien' => $request->nama_pasien,
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
                'tgl_pengajuan' => $today,
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
        $user = Auth::user();
        
        // Pasien hanya bisa melihat resep miliknya sendiri
        if ($user->isPasien() && $resep->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke resep ini.');
        }
        
        // Apoteker/Farmasi hanya bisa melihat resep di apoteknya
        if (($user->isApoteker() || $user->isFarmasi()) && $resep->apotek_id !== $user->apotek_id) {
            abort(403, 'Anda tidak memiliki akses ke resep ini.');
        }
        
        $resep->load(['user', 'items.obatalkes', 'items.signa', 'racikan.racikanItems.obatalkes', 'racikan.signa']);
        return view('resep.show', compact('resep'));
    }

    public function update(Request $request, Resep $resep)
    {
        $user = Auth::user();
        
        if (!$user->isApoteker() && !$user->isFarmasi()) {
            abort(403, 'Unauthorized');
        }

        // Apoteker/Farmasi hanya bisa update resep di apoteknya
        if ($resep->apotek_id !== $user->apotek_id) {
            abort(403, 'Anda tidak memiliki akses ke resep ini.');
        }

        $request->validate([
            'status' => 'required|in:diproses,selesai'
        ]);

        $resep->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'selesai' ? now() : null
        ]);

        // Send internal notification
        try {
            $notificationService = new NotificationService();
            if ($request->status === 'diproses') {
                $notificationService->sendPrescriptionProcessingNotification($resep);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send internal notification', [
                'resep_id' => $resep->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return redirect()->route('resep.index')->with('success', 'Status resep berhasil diupdate.');
    }

    public function destroy(Resep $resep)
    {
            $resep->delete();
            return redirect()->route('resep.index')->with('success', 'Resep berhasil dihapus.');
    }

    public function pdf(Resep $resep)
    {
        $user = Auth::user();
        
        // Pasien hanya bisa download PDF resep miliknya sendiri
        if ($user->isPasien() && $resep->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke resep ini.');
        }
        
        // Apoteker/Farmasi hanya bisa download PDF resep di apoteknya
        if (($user->isApoteker() || $user->isFarmasi()) && $resep->apotek_id !== $user->apotek_id) {
            abort(403, 'Anda tidak memiliki akses ke resep ini.');
        }
        
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

        // Apoteker/Farmasi hanya bisa complete resep di apoteknya
        if ($resep->apotek_id !== $user->apotek_id) {
            abort(403, 'Anda tidak memiliki akses ke resep ini.');
        }
        
        $resep->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Send WhatsApp notification
        try {
            $whatsappService = new WhatsAppService();
            $whatsappService->sendPrescriptionCompletionNotification($resep);
        } catch (\Exception $e) {
            // Log error but don't fail the completion
            \Log::error('Failed to send WhatsApp notification', [
                'resep_id' => $resep->id,
                'error' => $e->getMessage()
            ]);
        }

        // Send internal notification
        try {
            $notificationService = new NotificationService();
            $notificationService->sendPrescriptionCompletionNotification($resep);
        } catch (\Exception $e) {
            \Log::error('Failed to send internal notification', [
                'resep_id' => $resep->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return redirect()->route('resep.index')->with('success', 'Resep berhasil diselesaikan dan notifikasi telah dikirim.');
    }

    public function processing()
    {
        $user = Auth::user();
        
        if (!$user->isApoteker() && !$user->isFarmasi()) {
            abort(403, 'Unauthorized');
        }
        
        $reseps = Resep::with(['user', 'apotek'])
                      ->where('apotek_id', $user->apotek_id)
                      ->where('status', 'diproses')
                      ->latest()
                      ->paginate(10);
        
        return view('resep.processing', compact('reseps'));
    }

    public function completed()
    {
        $user = Auth::user();
        
        if (!$user->isApoteker() && !$user->isFarmasi()) {
            abort(403, 'Unauthorized');
        }
        
        $reseps = Resep::with(['user', 'apotek'])
                      ->where('apotek_id', $user->apotek_id)
                      ->where('status', 'selesai')
                      ->latest()
                      ->paginate(10);
        
        return view('resep.completed', compact('reseps'));
    }
} 