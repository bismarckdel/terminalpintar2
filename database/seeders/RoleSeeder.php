<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        $guru = Role::create(['name' => 'guru']);
        $orangtua = Role::create(['name' => 'orangtua']);

        // Buat Permission (Contoh)
        // Permission::create(['name' => 'kelola siswa']);
        // Permission::create(['name' => 'kelola berita'])
        

        // // Assign Permission ke Role
        // $admin->givePermissionTo(['kelola siswa', 'kelola berita']);
    }
}
