@extends('layouts.app')

@section('title', 'Resep Selesai')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Resep Selesai</h3>
        <div>
            <a href="{{ route('resep.index') }}" class="btn btn-secondary"><i class="fas fa-list"></i> Semua Resep</a>
            <a href="{{ route('resep.processing') }}" class="btn btn-info"><i class="fas fa-cog"></i> Sedang Diproses</a>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Pasien</th>
                <th>User</th>
                <th>Status</th>
                <th>Tanggal Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reseps as $resep)
            <tr>
                <td>{{ $loop->iteration + ($reseps->currentPage() - 1) * $reseps->perPage() }}</td>
                <td>{{ $resep->nama_pasien }}</td>
                <td>{{ $resep->user->name ?? '-' }}</td>
                <td>
                    <span class="badge bg-success">{{ $resep->status }}</span>
                </td>
                <td>{{ $resep->completed_at ? $resep->completed_at->format('d/m/Y H:i') : $resep->updated_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('resep.show', $resep) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('resep.pdf', $resep) }}" class="btn btn-success btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada resep yang sudah selesai.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $reseps->links() }}
    </div>
</div>
@endsection 