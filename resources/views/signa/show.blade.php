@extends('layouts.app')

@section('title', 'Detail Signa')

@section('content')
<div class="container mt-4">
    <h3>Detail Signa</h3>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Kode</dt>
                <dd class="col-sm-9">{{ $signa->signa_kode }}</dd>
                <dt class="col-sm-3">Nama</dt>
                <dd class="col-sm-9">{{ $signa->signa_nama }}</dd>
            </dl>
            <a href="{{ route('signa.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('signa.edit', $signa) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection 