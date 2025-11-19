<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File; // Untuk cek file

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tentukan lokasi file CSV
        // Pastikan Anda sudah membuat folder 'data' di dalam folder 'database'
        $csvFile = database_path('seeders/seedercsv/users.csv');

        // Cek apakah file ada
        if (!File::exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: $csvFile");
            return;
        }

        // 2. Buka file CSV (Mode Read)
        $handle = fopen($csvFile, "r");

        // 3. Lewati baris pertama (Header: nama, email, password, role)
        fgetcsv($handle);

        // 4. Loop setiap baris data
        while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
            
            // Mapping data dari CSV (sesuai urutan kolom)
            $nama = $data[0];
            $email = $data[1];
            $passwordRaw = $data[2];
            $roleName = $data[3];

            // 5. Buat User Baru
            // Menggunakan firstOrCreate agar tidak error jika dijalankan 2x (berdasarkan email)
            $user = User::firstOrCreate(
                ['email' => $email], // Kunci pencarian
                [
                    'nama' => $nama,
                    'password' => Hash::make($passwordRaw), // Enkripsi password dari CSV
                    // 'role_id' sudah dihapus sesuai instruksi sebelumnya
                ]
            );

            // 6. Assign Role menggunakan Spatie
            // Pastikan role tersebut SUDAH ADA di database (dibuat oleh RoleSeeder sebelumnya)
            if ($user->wasRecentlyCreated) {
                $user->assignRole($roleName);
                $this->command->info("User {$nama} ({$roleName}) berhasil dibuat.");
            } else {
                // Jika user sudah ada, kita bisa update role atau skip
                if (!$user->hasRole($roleName)) {
                    $user->assignRole($roleName);
                }
            }
        }

        // 7. Tutup file
        fclose($handle);
    }
}