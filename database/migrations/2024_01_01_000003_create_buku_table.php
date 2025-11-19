<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori', 100)->nullable();
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Hilang'])->default('Tersedia');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('buku');
    }
};