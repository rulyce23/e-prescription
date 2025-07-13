<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepRacikanItem extends Model
{
    protected $table = 'resep_racikan_items';
    protected $fillable = [
        'racikan_id', 'obatalkes_id', 'qty'
    ];

    public function racikan() { return $this->belongsTo(ResepRacikan::class, 'racikan_id'); }
    public function obatalkes() { return $this->belongsTo(ObatalkesM::class, 'obatalkes_id'); }
} 