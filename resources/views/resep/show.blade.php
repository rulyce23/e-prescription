@extends('layouts.app')

@section('title', 'Detail Resep')

@section('content')
<div class="container mt-4">
    <h3>Detail Resep</h3>
    <div class="card mb-3">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">No. Antrian</dt>
                <dd class="col-sm-9">{{ $resep->no_antrian ?? '-' }}</dd>
                <dt class="col-sm-3">Pasien</dt>
                <dd class="col-sm-9">{{ $resep->nama_pasien }}</dd>
                <dt class="col-sm-3">User</dt>
                <dd class="col-sm-9">{{ $resep->user->name ?? '-' }}</dd>
                <dt class="col-sm-3">Apotek</dt>
                <dd class="col-sm-9">{{ $resep->apotek->nama_apotek ?? '-' }}</dd>
                <dt class="col-sm-3">Tanggal Pengajuan</dt>
                <dd class="col-sm-9">{{ $resep->tgl_pengajuan ? date('d/m/Y', strtotime($resep->tgl_pengajuan)) : '-' }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ $resep->status }}</dd>
                <dt class="col-sm-3">Tanggal Dibuat</dt>
                <dd class="col-sm-9">{{ $resep->created_at->format('d/m/Y H:i') }}</dd>
                <dt class="col-sm-3">Keluhan</dt>
                <dd class="col-sm-9">{{ $resep->keluhan ?? '-' }}</dd>
                <dt class="col-sm-3">Diagnosa</dt>
                <dd class="col-sm-9">{{ $resep->diagnosa ?? '-' }}</dd>
            </dl>
            <a href="{{ route('resep.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('resep.pdf', $resep) }}" class="btn btn-success"><i class="fas fa-file-pdf"></i> Cetak PDF</a>
        </div>
    </div>
    <h5>Obat Non Racikan</h5>
    <table class="table table-bordered">
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
    <h5>Obat Racikan</h5>
    <table class="table table-bordered">
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
</div>
@endsection 