<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')->constrained('jadwal_sesi')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha']);
            $table->timestamps();
            
            $table->unique(['sesi_id', 'siswa_id']); // Unique key
        });
    }
    public function down(): void {
        Schema::dropIfExists('kehadiran');
    }
};