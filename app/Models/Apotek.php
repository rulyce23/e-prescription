<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apotek extends Model
{
    protected $table = 'apotek';

    protected $fillable = [
        'nama_apotek',
        'alamat',
        'telepon',
        'whatsapp',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get users associated with this apotek
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get prescriptions for this apotek
     */
    public function reseps()
    {
        return $this->hasMany(Resep::class);
    }

    /**
     * Get active apotekers for this apotek
     */
    public function apotekers()
    {
        return $this->users()->where('role', 'apoteker');
    }

    /**
     * Get active farmasis for this apotek
     */
    public function farmasis()
    {
        return $this->users()->where('role', 'farmasi');
    }

    /**
     * Scope for active apotek
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 