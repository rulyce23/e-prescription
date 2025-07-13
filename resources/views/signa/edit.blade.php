@extends('layouts.app')

@section('title', 'Edit Signa')

@section('content')
<div class="container mt-4">
    <h3>Edit Signa</h3>
    <form action="{{ route('signa.update', $signa) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="signa_kode" class="form-label">Kode</label>
            <input type="text" class="form-control @error('signa_kode') is-invalid @enderror" id="signa_kode" name="signa_kode" value="{{ old('signa_kode', $signa->signa_kode) }}" required>
            @error('signa_kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="signa_nama" class="form-label">Nama</label>
            <input type="text" class="form-control @error('signa_nama') is-invalid @enderror" id="signa_nama" name="signa_nama" value="{{ old('signa_nama', $signa->signa_nama) }}" required>
            @error('signa_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('signa.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection 