@extends('layouts.app')

@section('title', 'Daftar Resep')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Daftar Resep</h3>
        <div>
            @if(Auth::user()->isApoteker() || Auth::user()->isFarmasi())
                <span class="badge bg-info me-2">
                    <i class="fas fa-store"></i> {{ Auth::user()->apotek->nama_apotek ?? 'Apotek' }}
                </span>
                <a href="{{ route('resep.processing') }}" class="btn btn-info"><i class="fas fa-cog"></i> Sedang Diproses</a>
                <a href="{{ route('resep.completed') }}" class="btn btn-success"><i class="fas fa-check"></i> Selesai</a>
            @endif
            @if(Auth::user()->isPasien())
                <a href="{{ route('resep.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Resep</a>
            @endif
            <a href="{{ route('resep.export', ['apotek_id' => $apotekId, 'status' => $status]) }}" class="btn btn-warning"><i class="fas fa-file-excel"></i> Export Excel</a>
        </div>
    </div>

    <!-- Filter Section -->
    @if(Auth::user()->isAdmin() || Auth::user()->isDokter())
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('resep.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="apotek_id" class="form-label">Filter Apotek</label>
                    <select name="apotek_id" id="apotek_id" class="form-select">
                        <option value="">Semua Apotek</option>
                        @foreach($apoteks as $apotek)
                            <option value="{{ $apotek->id }}" {{ $apotekId == $apotek->id ? 'selected' : '' }}>
                                {{ $apotek->nama_apotek }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Filter Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('resep.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>No. Antrian</th>
                <th>Pasien</th>
                <th>Apotek</th>
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
                <td>{{ $resep->no_antrian ?? '-' }}</td>
                <td>{{ $resep->nama_pasien }}</td>
                <td>{{ $resep->apotek->nama_apotek ?? '-' }}</td>
                <td>{{ $resep->user->name ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ $resep->status === 'pending' ? 'warning' : ($resep->status === 'diproses' ? 'info' : 'success') }}">
                        {{ $resep->status }}
                    </span>
                </td>
                <td>{{ $resep->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('resep.show', $resep) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    @if(Auth::user()->isApoteker() && $resep->status === 'pending')
                        <form action="{{ route('resep.update', $resep) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="diproses">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Proses resep ini?')">
                                <i class="fas fa-cog"></i> Proses
                            </button>
                        </form>
                        <form action="{{ route('resep.update', $resep) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Selesaikan resep ini?')">
                                <i class="fas fa-check"></i> Selesai
                            </button>
                        </form>
                    @endif
                    @if($resep->status === 'selesai')
                        <a href="{{ route('resep.pdf', $resep) }}" class="btn btn-success btn-sm"><i class="fas fa-file-pdf"></i></a>
                    @endif
                    @if(Auth::user()->isAdmin() || Auth::user()->isDokter())
                        <form action="{{ route('resep.destroy', $resep) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus resep ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada resep.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $reseps->links() }}
    </div>
</div>
@endsection 