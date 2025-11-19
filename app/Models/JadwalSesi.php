<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalSesi extends Model
{
    protected $table = 'jadwal_sesi';
    protected $guarded = ['id'];

    // --- RELASI SESUAI ERD ---
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function materi(): HasMany // Relasi "memiliki materi"
    {
        return $this->hasMany(Materi::class, 'sesi_id');
    }

    public function kehadiran(): HasMany // Relasi "diikuti"
    {
        return $this->hasMany(Kehadiran::class, 'sesi_id');
    }
}