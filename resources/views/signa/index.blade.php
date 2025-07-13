@extends('layouts.app')

@section('title', 'Daftar Signa')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Daftar Signa</h3>
        <a href="{{ route('signa.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Signa</a>
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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($signa as $sg)
            <tr>
                <td>{{ $loop->iteration + ($signa->currentPage() - 1) * $signa->perPage() }}</td>
                <td>{{ $sg->signa_kode }}</td>
                <td>{{ $sg->signa_nama }}</td>
                <td>
                    <a href="{{ route('signa.show', $sg) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('signa.edit', $sg) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('signa.destroy', $sg) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus signa ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada data signa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $signa->links() }}
    </div>
</div>
@endsection 