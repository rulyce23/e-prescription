@extends('layouts.app')

@section('title', 'Detail Obat/Alkes')

@section('content')
<div class="container mt-4">
    <h3>Detail Obat/Alkes</h3>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Kode</dt>
                <dd class="col-sm-9">{{ $obatalkes->obatalkes_kode }}</dd>
                <dt class="col-sm-3">Nama</dt>
                <dd class="col-sm-9">{{ $obatalkes->obatalkes_nama }}</dd>
                <dt class="col-sm-3">Stok</dt>
                <dd class="col-sm-9">{{ $obatalkes->stok }}</dd>
            </dl>
            <a href="{{ route('obatalkes.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('obatalkes.edit', $obatalkes) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection 