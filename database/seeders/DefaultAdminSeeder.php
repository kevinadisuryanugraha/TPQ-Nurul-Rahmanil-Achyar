<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use App\Models\Level;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DefaultAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Spatie roles for guard 'admin'
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);

        // 2. Create Spatie role for guard 'web'
        $muridRole = Role::firstOrCreate(['name' => 'murid', 'guard_name' => 'web']);

        // 3. Create the default superadmin user
        $superadmin = Admin::updateOrCreate(
            ['email' => 'superadmin@lms-tpq.com'],
            [
                'nama' => 'Superadmin IT',
                'password' => bcrypt('password'), // Will be hashed automatically by cast if model casts it, or manually here
                'no_hp' => '081234567890',
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        // 4. Assign Spatie role to default superadmin
        if (!$superadmin->hasRole('superadmin')) {
            $superadmin->assignRole($superadminRole);
        }

        // 5. Create a default admin user for testing
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@lms-tpq.com'],
            [
                'nama' => 'Ustadz Ahmad',
                'password' => bcrypt('password'),
                'no_hp' => '081298765432',
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // 6. Create a default student user for testing
        $level = Level::where('urutan', 4)->first() ?? Level::first();
        $student = User::updateOrCreate(
            ['username' => 'budi'],
            [
                'nama_lengkap' => 'Budi Santoso',
                'nama_panggilan' => 'Budi',
                'password' => bcrypt('password'),
                'jenis_kelamin' => 'L',
                'tanggal_masuk' => now(),
                'current_level_id' => $level->id,
                'is_active' => true,
            ]
        );

        if (!$student->hasRole('murid')) {
            $student->assignRole($muridRole);
        }
    }
}
