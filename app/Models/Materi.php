<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materi extends Model
{
    protected $table = 'materi';
    protected $guarded = ['id'];

    // --- RELASI SESUAI ERD ---
    public function jadwalSesi(): BelongsTo
    {
        return $this->belongsTo(JadwalSesi::class, 'sesi_id');
    }
}