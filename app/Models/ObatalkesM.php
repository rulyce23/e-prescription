<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObatalkesM extends Model
{
    protected $table = 'obatalkes_m';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'obatalkes_kode',
        'obatalkes_nama',
        'stok',
        'additional_data',
        'is_active',
        'is_deleted',
    ];

    public function resepItems()
    {
        return $this->hasMany(ResepItem::class, 'obatalkes_id');
    }
    public function racikanItems()
    {
        return $this->hasMany(ResepRacikanItem::class, 'obatalkes_id');
    }
} 