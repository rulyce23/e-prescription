<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resep PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 80px; margin-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #333; padding: 6px; text-align: left; }
        .footer { text-align: right; font-size: 11px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('favicon.ico') }}" class="logo" alt="Logo">
        <div class="title">E-Prescription - Resep Digital</div>
        <div>www.e-prescription.local</div>
    </div>
    <table class="info-table">
        <tr>
            <td><strong>No. Antrian</strong></td>
            <td>{{ $resep->no_antrian ?? '-' }}</td>
            <td><strong>Pasien</strong></td>
            <td>{{ $resep->nama_pasien }}</td>
        </tr>
        <tr>
            <td><strong>User</strong></td>
            <td>{{ $resep->user->name ?? '-' }}</td>
            <td><strong>Status</strong></td>
            <td>{{ $resep->status }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Pengajuan</strong></td>
            <td>{{ $resep->tgl_pengajuan ? date('d/m/Y', strtotime($resep->tgl_pengajuan)) : '-' }}</td>
            <td><strong>Tanggal Dibuat</strong></td>
            <td>{{ $resep->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Keluhan</strong></td>
            <td colspan="3">{{ $resep->keluhan ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Diagnosa</strong></td>
            <td colspan="3">{{ $resep->diagnosa ?? '-' }}</td>
        </tr>
    </table>
    <h4>Obat Non Racikan</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Obat</th>
                <th>Signa</th>
                <th>Qty</th>
                <th>Aturan Pakai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resep->items as $item)
            <tr>
                <td>{{ $item->obatalkes->obatalkes_nama ?? '-' }}</td>
                <td>{{ $item->signa->signa_nama ?? '-' }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->aturan_pakai }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">-</td></tr>
            @endforelse
        </tbody>
    </table>
    <h4>Obat Racikan</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Racikan</th>
                <th>Obat</th>
                <th>Qty</th>
                <th>Signa</th>
                <th>Aturan Pakai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resep->racikan as $racik)
                @foreach($racik->racikanItems as $i => $rItem)
                <tr>
                    @if($i == 0)
                        <td rowspan="{{ $racik->racikanItems->count() }}">{{ $racik->nama_racikan }}</td>
                    @endif
                    <td>{{ $rItem->obatalkes->obatalkes_nama ?? '-' }}</td>
                    <td>{{ $rItem->qty }}</td>
                    @if($i == 0)
                        <td rowspan="{{ $racik->racikanItems->count() }}">{{ $racik->signa->signa_nama ?? '-' }}</td>
                        <td rowspan="{{ $racik->racikanItems->count() }}">{{ $racik->aturan_pakai }}</td>
                    @endif
                </tr>
                @endforeach
            @empty
            <tr><td colspan="5" class="text-center">-</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html> 