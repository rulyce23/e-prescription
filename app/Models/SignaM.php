<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignaM extends Model
{
    protected $table = 'signa_m';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'signa_kode',
        'signa_nama',
        'additional_data',
        'is_active',
        'is_deleted',
    ];

    public function resepItems()
    {
        return $this->hasMany(ResepItem::class, 'signa_m_id');
    }
    public function racikan()
    {
        return $this->hasMany(ResepRacikan::class, 'signa_m_id');
    }
} 