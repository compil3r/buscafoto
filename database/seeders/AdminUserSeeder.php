<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Cria ou atualiza o usuário administrador.
     *
     * Configure no .env: ADMIN_EMAIL, ADMIN_NAME, ADMIN_PASSWORD.
     * Se ADMIN_PASSWORD estiver vazio e APP_ENV=local, usa "password" (troque já).
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@buscafoto.local');
        $password = env('ADMIN_PASSWORD');

        if (! $password && app()->environment('local')) {
            $password = 'password';
        }

        if (! $password) {
            $this->command?->warn('AdminUserSeeder: defina ADMIN_PASSWORD no .env para criar o administrador.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Administrador'),
                'password' => Hash::make($password),
                'is_admin' => true,
                'phone' => env('ADMIN_PHONE'),
                'accepts_marketing' => false,
            ]
        );
    }
}
