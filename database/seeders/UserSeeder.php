<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun contoh berpassword "password" TIDAK boleh ada di server production.
        if (app()->isProduction()) {
            $this->command?->warn('UserSeeder dilewati di production. Buat admin dengan: php artisan admin:create email@domain.com');

            return;
        }

        // PERINGATAN: akun di bawah hanya untuk DEVELOPMENT.
        // Ganti password (atau hapus akun) sebelum website dipakai di production.
        // Password otomatis di-hash oleh cast "hashed" pada model User.
        User::create([
            'name' => 'Administrator Desa',
            'email' => 'admin@desaklepu.test',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Redaksi Desa',
            'email' => 'editor@desaklepu.test',
            'password' => 'password',
            'role' => 'editor',
            'is_active' => true,
        ]);
    }
}
