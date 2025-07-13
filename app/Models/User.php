<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the prescriptions created by this user
     */
    public function prescriptions()
    {
        return $this->hasMany(Resep::class, 'user_id');
    }

    public function reseps()
    {
        return $this->hasMany(Resep::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is dokter
     */
    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    /**
     * Check if user is apoteker
     */
    public function isApoteker()
    {
        return $this->role === 'apoteker';
    }

    /**
     * Check if user is pasien
     */
    public function isPasien()
    {
        return $this->role === 'pasien';
    }

    /**
     * Check if user can create prescriptions
     */
    public function canCreatePrescription()
    {
        return in_array($this->role, ['admin', 'dokter', 'pasien']);
    }

    /**
     * Check if user can create prescriptions (alias for backward compatibility)
     */
    public function canCreatePrescriptions()
    {
        return $this->canCreatePrescription();
    }

    /**
     * Check if user can approve prescriptions
     */
    public function canApprovePrescription()
    {
        return in_array($this->role, ['admin', 'dokter']);
    }

    /**
     * Check if user can approve prescriptions (alias for backward compatibility)
     */
    public function canApprovePrescriptions()
    {
        return $this->canApprovePrescription();
    }

    /**
     * Check if user can receive prescriptions
     */
    public function canReceivePrescription()
    {
        return $this->role === 'apoteker';
    }

    /**
     * Check if user can manage master data (signa and obatalkes)
     */
    public function canManageMasterData()
    {
        return in_array($this->role, ['admin', 'dokter', 'apoteker']);
    }

    /**
     * Check if user has full access (admin and dokter)
     */
    public function hasFullAccess()
    {
        return in_array($this->role, ['admin', 'dokter']);
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName()
    {
        $roles = [
            'admin' => 'Administrator',
            'dokter' => 'Dokter',
            'apoteker' => 'Apoteker',
            'pasien' => 'Pasien'
        ];
        
        return $roles[$this->role] ?? $this->role;
    }

    public function canInputResep()
    {
        return in_array($this->role, ['admin', 'dokter', 'pasien']);
    }
}
