@extends('layouts.app')

@section('title', 'Tambah Obat/Alkes')

@section('content')
<div class="container mt-4">
    <h3>Tambah Obat/Alkes</h3>
    <form action="{{ route('obatalkes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="obatalkes_kode" class="form-label">Kode</label>
            <input type="text" class="form-control @error('obatalkes_kode') is-invalid @enderror" id="obatalkes_kode" name="obatalkes_kode" value="{{ old('obatalkes_kode') }}" required>
            @error('obatalkes_kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="obatalkes_nama" class="form-label">Nama</label>
            <input type="text" class="form-control @error('obatalkes_nama') is-invalid @enderror" id="obatalkes_nama" name="obatalkes_nama" value="{{ old('obatalkes_nama') }}" required>
            @error('obatalkes_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok') }}" min="0" required>
            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('obatalkes.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection 