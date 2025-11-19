<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- 1. Tambahkan ini
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'siswa'; 
    
    protected $fillable = [
        'nama_lengkap',
        'status',
        'orang_tua_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // --- RELASI SESUAI ERD ---
    
    public function orangTua(): BelongsTo // Relasi "dimiliki" (oleh Orang Tua)
    {
        return $this->belongsTo(User::class, 'orang_tua_id');
    }
    
    public function kehadiran(): HasMany // Relasi "mengikuti"
    {
        return $this->hasMany(Kehadiran::class, 'siswa_id');
    }

    public function catatanGuru(): HasMany // Relasi "menerima" catatan
    {
        return $this->hasMany(CatatanGuru::class, 'siswa_id');
    }
}