@extends('layouts.app')

@section('title', 'Daftar Obat/Alkes')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Daftar Obat/Alkes</h3>
        <a href="{{ route('obatalkes.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Obat</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($obatalkes as $obat)
            <tr>
                <td>{{ $loop->iteration + ($obatalkes->currentPage() - 1) * $obatalkes->perPage() }}</td>
                <td>{{ $obat->obatalkes_kode }}</td>
                <td>{{ $obat->obatalkes_nama }}</td>
                <td>{{ $obat->stok }}</td>
                <td>
                    <a href="{{ route('obatalkes.show', $obat) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('obatalkes.edit', $obat) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('obatalkes.destroy', $obat) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus obat ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data obat/alkes.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $obatalkes->links() }}
    </div>
</div>
@endsection 