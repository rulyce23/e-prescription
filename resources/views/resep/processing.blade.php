@extends('layouts.app')

@section('title', 'Resep Sedang Diproses')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Resep Sedang Diproses</h3>
        <div>
            <a href="{{ route('resep.index') }}" class="btn btn-secondary"><i class="fas fa-list"></i> Semua Resep</a>
            <a href="{{ route('resep.completed') }}" class="btn btn-success"><i class="fas fa-check"></i> Resep Selesai</a>
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
                <th>Tanggal</th>
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
                    <span class="badge bg-info">{{ $resep->status }}</span>
                </td>
                <td>{{ $resep->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('resep.show', $resep) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    <form action="{{ route('resep.update', $resep) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Selesaikan resep ini?')">
                            <i class="fas fa-check"></i> Selesai
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada resep yang sedang diproses.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $reseps->links() }}
    </div>
</div>
@endsection 