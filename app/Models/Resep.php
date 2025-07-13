<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resep extends Model
{
    use SoftDeletes;

    protected $table = 'resep';

    protected $fillable = [
        'user_id',
        'obatalkes_id',
        'signa_m_id',
        'nama_pasien',
        'jumlah',
        'aturan_pakai',
        'status',
        'keluhan',
        'diagnosa',
        'approved_by',
        'rejected_by',
        'received_by',
        'approved_at',
        'rejected_at',
        'received_at',
        'completed_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function obatalkes()
    {
        return $this->belongsTo(ObatalkesM::class, 'obatalkes_id');
    }

    public function signa()
    {
        return $this->belongsTo(SignaM::class, 'signa_m_id');
    }

    public function items()
    {
        return $this->hasMany(ResepItem::class);
    }

    public function racikan()
    {
        return $this->hasMany(ResepRacikan::class);
    }

    // Scope methods for status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
} 