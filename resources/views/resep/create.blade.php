@extends('layouts.app')

@section('title', 'Buat Resep Baru')

@push('styles')
<style>
    .racikan-card { border: 1px solid #dee2e6; border-radius: 8px; padding: 16px; margin-bottom: 16px; background: #f8f9fa; }
    .racikan-title { font-weight: bold; margin-bottom: 8px; }
    .draft-table th, .draft-table td { vertical-align: middle !important; }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h3>Buat Resep Baru</h3>
    <form id="resepForm" action="{{ route('resep.store') }}" method="POST">
    @csrf
        <div class="mb-3">
            <label for="nama_pasien" class="form-label">Nama Pasien</label>
            <input type="text" class="form-control @error('nama_pasien') is-invalid @enderror" id="nama_pasien" name="nama_pasien" value="{{ old('nama_pasien') }}" required>
            @error('nama_pasien')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
                    <div class="mb-3">
            <label for="keluhan" class="form-label">Keluhan</label>
            <textarea class="form-control @error('keluhan') is-invalid @enderror" id="keluhan" name="keluhan" required>{{ old('keluhan') }}</textarea>
            @error('keluhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="diagnosa" class="form-label">Diagnosa</label>
            <textarea class="form-control @error('diagnosa') is-invalid @enderror" id="diagnosa" name="diagnosa" required>{{ old('diagnosa') }}</textarea>
            @error('diagnosa')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <hr>
        <h5>Obat Non Racikan</h5>
        <div id="nonRacikanList"></div>
        <button type="button" class="btn btn-outline-primary mb-3" onclick="addNonRacikan()"><i class="fas fa-plus"></i> Tambah Obat Non Racikan</button>
        <hr>
        <h5>Obat Racikan</h5>
        <div id="racikanList"></div>
        <button type="button" class="btn btn-outline-success mb-3" onclick="addRacikan()"><i class="fas fa-plus"></i> Tambah Racikan</button>
        <hr>
        <h5>Draft Resep</h5>
        <div id="draftTable"></div>
        <button type="submit" class="btn btn-primary mt-3">Simpan Resep</button>
        <a href="{{ route('resep.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>
    </div>
@endsection

@push('scripts')
<script>
const obatalkes = @json($obatalkes);
const signa = @json($signa);
let nonRacikan = [];
let racikan = [];

function addNonRacikan() {
    nonRacikan.push({ obatalkes_id: '', signa_m_id: '', qty: 1, aturan_pakai: '' });
    renderNonRacikan();
    renderDraft();
}
function removeNonRacikan(idx) {
    nonRacikan.splice(idx, 1);
    renderNonRacikan();
    renderDraft();
}
function updateNonRacikan(idx, field, value) {
    nonRacikan[idx][field] = value;
    renderDraft();
}
function renderNonRacikan() {
    let html = '';
    nonRacikan.forEach((item, idx) => {
        html += `<div class='row mb-2'>
            <div class='col-md-4'>
                <select class='form-select' onchange='updateNonRacikan(${idx}, "obatalkes_id", this.value)'>
                    <option value=''>Pilih Obat</option>`;
        obatalkes.forEach(o => {
            const disabled = o.stok <= 0 ? 'disabled' : '';
            html += `<option value='${o.id}' ${item.obatalkes_id == o.id ? 'selected' : ''} ${disabled}>${o.obatalkes_nama} (Stok: ${o.stok})</option>`;
        });
        html += `</select></div>
            <div class='col-md-2'>
                <input type='number' min='1' max='${getMaxStok(item.obatalkes_id)}' class='form-control' placeholder='Qty' value='${item.qty}' onchange='updateNonRacikan(${idx}, "qty", this.value)'>
            </div>
            <div class='col-md-3'>
                <select class='form-select' onchange='updateNonRacikan(${idx}, "signa_m_id", this.value)'>
                    <option value=''>Pilih Signa</option>`;
        signa.forEach(s => {
            html += `<option value='${s.id}' ${item.signa_m_id == s.id ? 'selected' : ''}>${s.signa_nama}</option>`;
        });
        html += `</select></div>
            <div class='col-md-2'>
                <input type='text' class='form-control' placeholder='Aturan Pakai' value='${item.aturan_pakai}' onchange='updateNonRacikan(${idx}, "aturan_pakai", this.value)'>
            </div>
            <div class='col-md-1 d-flex align-items-center'>
                <button type='button' class='btn btn-danger btn-sm' onclick='removeNonRacikan(${idx})'><i class='fas fa-trash'></i></button>
            </div>
        </div>`;
    });
    document.getElementById('nonRacikanList').innerHTML = html;
}
function getMaxStok(obatId) {
    const o = obatalkes.find(x => x.id == obatId);
    return o ? o.stok : 1;
}
// Racikan
function addRacikan() {
    racikan.push({ nama_racikan: '', signa_m_id: '', qty: 1, aturan_pakai: '', items: [{ obatalkes_id: '', qty: 1 }] });
    renderRacikan();
    renderDraft();
}
function removeRacikan(idx) {
    racikan.splice(idx, 1);
    renderRacikan();
    renderDraft();
}
function updateRacikan(idx, field, value) {
    racikan[idx][field] = value;
    renderDraft();
}
function addRacikanItem(ridx) {
    racikan[ridx].items.push({ obatalkes_id: '', qty: 1 });
    renderRacikan();
    renderDraft();
}
function removeRacikanItem(ridx, iidx) {
    racikan[ridx].items.splice(iidx, 1);
    renderRacikan();
    renderDraft();
}
function updateRacikanItem(ridx, iidx, field, value) {
    racikan[ridx].items[iidx][field] = value;
    renderDraft();
}
function renderRacikan() {
    let html = '';
    racikan.forEach((r, ridx) => {
        html += `<div class='racikan-card'>
            <div class='row mb-2'>
                <div class='col-md-4'><input type='text' class='form-control' placeholder='Nama Racikan' value='${r.nama_racikan}' onchange='updateRacikan(${ridx}, "nama_racikan", this.value)'></div>
                <div class='col-md-2'><input type='number' min='1' class='form-control' placeholder='Qty' value='${r.qty}' onchange='updateRacikan(${ridx}, "qty", this.value)'></div>
                <div class='col-md-3'><select class='form-select' onchange='updateRacikan(${ridx}, "signa_m_id", this.value)'><option value=''>Pilih Signa</option>`;
        signa.forEach(s => {
            html += `<option value='${s.id}' ${r.signa_m_id == s.id ? 'selected' : ''}>${s.signa_nama}</option>`;
        });
        html += `</select></div>
                <div class='col-md-2'><input type='text' class='form-control' placeholder='Aturan Pakai' value='${r.aturan_pakai}' onchange='updateRacikan(${ridx}, "aturan_pakai", this.value)'></div>
                <div class='col-md-1 d-flex align-items-center'><button type='button' class='btn btn-danger btn-sm' onclick='removeRacikan(${ridx})'><i class='fas fa-trash'></i></button></div>
            </div>`;
        html += `<div class='mb-2'><strong>Obat Racikan:</strong></div>`;
        r.items.forEach((item, iidx) => {
            html += `<div class='row mb-2'>
                <div class='col-md-6'><select class='form-select' onchange='updateRacikanItem(${ridx},${iidx},"obatalkes_id",this.value)'><option value=''>Pilih Obat</option>`;
            obatalkes.forEach(o => {
                const disabled = o.stok <= 0 ? 'disabled' : '';
                html += `<option value='${o.id}' ${item.obatalkes_id == o.id ? 'selected' : ''} ${disabled}>${o.obatalkes_nama} (Stok: ${o.stok})</option>`;
            });
            html += `</select></div>
                <div class='col-md-3'><input type='number' min='1' max='${getMaxStok(item.obatalkes_id)}' class='form-control' placeholder='Qty' value='${item.qty}' onchange='updateRacikanItem(${ridx},${iidx},"qty",this.value)'></div>
                <div class='col-md-2 d-flex align-items-center'><button type='button' class='btn btn-danger btn-sm' onclick='removeRacikanItem(${ridx},${iidx})'><i class='fas fa-trash'></i></button></div>
            </div>`;
        });
        html += `<button type='button' class='btn btn-outline-secondary btn-sm' onclick='addRacikanItem(${ridx})'><i class='fas fa-plus'></i> Tambah Obat Racikan</button>`;
        html += `</div>`;
    });
    document.getElementById('racikanList').innerHTML = html;
}
function renderDraft() {
    let html = '<table class="table table-bordered draft-table"><thead><tr><th>Jenis</th><th>Obat/Racikan</th><th>Qty</th><th>Signa</th><th>Aturan Pakai</th></tr></thead><tbody>';
    nonRacikan.forEach(item => {
        const obat = obatalkes.find(o => o.id == item.obatalkes_id);
        const signaObj = signa.find(s => s.id == item.signa_m_id);
        html += `<tr><td>Non Racikan</td><td>${obat ? obat.obatalkes_nama : '-'}</td><td>${item.qty}</td><td>${signaObj ? signaObj.signa_nama : '-'}</td><td>${item.aturan_pakai}</td></tr>`;
    });
    racikan.forEach(r => {
        let racikNama = r.nama_racikan;
        let signaObj = signa.find(s => s.id == r.signa_m_id);
        let obatList = r.items.map(item => {
            const obat = obatalkes.find(o => o.id == item.obatalkes_id);
            return `${obat ? obat.obatalkes_nama : '-'} (${item.qty})`;
        }).join(', ');
        html += `<tr><td>Racikan</td><td>${racikNama} <br><small>${obatList}</small></td><td>${r.qty}</td><td>${signaObj ? signaObj.signa_nama : '-'}</td><td>${r.aturan_pakai}</td></tr>`;
    });
    html += '</tbody></table>';
    document.getElementById('draftTable').innerHTML = html;
}
// On submit, inject hidden inputs
const resepForm = document.getElementById('resepForm');
resepForm.addEventListener('submit', function(e) {
    // Remove old hidden
    document.querySelectorAll('.dynamic-hidden').forEach(el => el.remove());
    // Non racikan
    nonRacikan.forEach((item, idx) => {
        for (const key in item) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `items[${idx}][${key}]`;
            input.value = item[key];
            input.classList.add('dynamic-hidden');
            resepForm.appendChild(input);
        }
    });
    // Racikan
    racikan.forEach((r, ridx) => {
        for (const key of ['nama_racikan','signa_m_id','qty','aturan_pakai']) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `racikan[${ridx}][${key}]`;
            input.value = r[key];
            input.classList.add('dynamic-hidden');
            resepForm.appendChild(input);
        }
        r.items.forEach((item, iidx) => {
            for (const key in item) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `racikan[${ridx}][items][${iidx}][${key}]`;
                input.value = item[key];
                input.classList.add('dynamic-hidden');
                resepForm.appendChild(input);
            }
        });
    });
});
// Init
addNonRacikan();
renderRacikan();
renderDraft();
</script>
@endpush 