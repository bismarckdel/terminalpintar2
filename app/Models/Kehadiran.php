<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';
    protected $guarded = ['id'];

    // --- RELASI SESUAI ERD ---
    public function jadwalSesi(): BelongsTo
    {
        return $this->belongsTo(JadwalSesi::class, 'sesi_id');
    }
    
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}