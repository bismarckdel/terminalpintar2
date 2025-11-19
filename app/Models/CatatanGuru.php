<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanGuru extends Model
{
    protected $table = 'catatan_guru';
    protected $guarded = ['id'];

    // --- RELASI SESUAI ERD ---
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
    
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}