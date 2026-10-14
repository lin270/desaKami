<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email : Email akun admin} {--name=Administrator Desa : Nama lengkap}';

    protected $description = 'Buat akun admin baru, atau reset password dan aktifkan akun yang sudah ada';

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Format email tidak valid.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Password baru (minimal 8 karakter)');
        $confirm = (string) $this->secret('Ulangi password');

        if (strlen($password) < 8) {
            $this->error('Password minimal 8 karakter.');

            return self::FAILURE;
        }

        if ($password !== $confirm) {
            $this->error('Konfirmasi password tidak cocok.');

            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            // Password di-hash otomatis oleh cast "hashed" pada model User
            $user->update(['password' => $password, 'role' => 'admin', 'is_active' => true]);
            $this->info("Akun {$email} diperbarui: password diganti, role admin, aktif.");
        } else {
            User::create([
                'name' => (string) $this->option('name'),
                'email' => $email,
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
            ]);
            $this->info("Akun admin {$email} dibuat.");
        }

        return self::SUCCESS;
    }
}
