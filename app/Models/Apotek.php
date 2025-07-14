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

    private function formatPhoneNumber($phoneNumber)
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Jika mulai dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Jika sudah mulai dengan 62, biarkan
        // Jika tidak, tambahkan 62 di depan
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }
} 