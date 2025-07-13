<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepItem extends Model
{
    protected $table = 'resep_items';
    protected $fillable = [
        'resep_id', 'obatalkes_id', 'signa_m_id', 'qty', 'aturan_pakai'
    ];

    public function resep() { return $this->belongsTo(Resep::class); }
    public function obatalkes() { return $this->belongsTo(ObatalkesM::class, 'obatalkes_id'); }
    public function signa() { return $this->belongsTo(SignaM::class, 'signa_m_id'); }
} 