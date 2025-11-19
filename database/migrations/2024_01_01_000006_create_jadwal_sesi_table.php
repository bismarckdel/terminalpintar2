<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jadwal_sesi', function (Blueprint $table) {
            $table->id();
            $table->string('topik');
            $table->text('deskripsi')->nullable();
            $table->dateTime('waktu_mulai');
            $table->foreignId('guru_id')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('jadwal_sesi');
    }
};