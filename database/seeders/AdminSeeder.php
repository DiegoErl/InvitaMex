<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existe un admin
        $adminExists = User::where('email', 'admin@invitacleth.com')->exists();

        if ($adminExists) {
            $this->command->warn('⚠️  El admin ya existe');
            return;
        }

        // Crear admin principal
        User::create([
            'firstName' => 'Admin',
            'lastName' => 'Principal',
            'email' => 'admin@invitacleth.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Admin creado exitosamente');
        $this->command->info('📧 Email: admin@invitacleth.com');
        $this->command->info('🔑 Password: admin123');
        $this->command->warn('⚠️  IMPORTANTE: Cambia esta contraseña en producción');
    }
}