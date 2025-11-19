<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- 1. Tambahkan ini (sesuai DDL)
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    // 2. Tambahkan SoftDeletes
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles; 

    protected $table = 'users'; // <-- 3. Tentukan nama tabel
    protected $fillable = [
        'nama',
        'email',
        'password',
    ];

    // public function role(): BelongsTo
    // {
    //     return $this->belongsTo(Role::class, 'role_id');
    // }

    // public function berita(): HasMany // Relasi "mempublikasikan"
    // {
    //     return $this->hasMany(Berita::class, 'penulis_id');
    // }
    
    // public function siswa(): HasMany // Relasi "memiliki" (sebagai Orang Tua)
    // {
    //     return $this->hasMany(Siswa::class, 'orang_tua_id');
    // }

    // public function jadwalMengajar(): HasMany // Relasi "mengajar" (sebagai Guru)
    // {
    //     return $this->hasMany(JadwalSesi::class, 'guru_id');
    // }
    
    // public function catatanGuru(): HasMany // Relasi "memberi catatan" (sebagai Guru)
    // {
    //     return $this->hasMany(CatatanGuru::class, 'guru_id');
    // }
    
    // public function peminjaman(): HasMany // Relasi "meminjam"
    // {
    //     return $this->hasMany(Peminjaman::class, 'peminjam_id');
    // }
}