<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepRacikan extends Model
{
    protected $table = 'resep_racikan';
    protected $fillable = [
        'resep_id', 'nama_racikan', 'signa_m_id', 'aturan_pakai', 'qty'
    ];

    public function resep() { return $this->belongsTo(Resep::class); }
    public function signa() { return $this->belongsTo(SignaM::class, 'signa_m_id'); }
    public function racikanItems() { return $this->hasMany(ResepRacikanItem::class, 'racikan_id'); }
} 