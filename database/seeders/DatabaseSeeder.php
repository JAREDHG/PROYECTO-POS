<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ejecutar la creación de roles y permisos
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Crear tu usuario administrador (o recuperarlo si ya existe)
        $admin = User::firstOrCreate(
            ['email' => 'admin@pos.com'],
            [
                'name' => 'Admin POS',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Asignarle el rol de administrador
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // 4. Crear el usuario Cajero (NUEVO)
        $cashier = User::firstOrCreate(
            ['email' => 'cajero@pos.com'],
            [
                'name' => 'Cajero de Turno',
                'password' => Hash::make('password123'),
            ]
        );

        // 5. Asignarle el rol de cajero (NUEVO)
        if (!$cashier->hasRole('cashier')) {
            $cashier->assignRole('cashier');
        }

        // 6. Inyectar el catálogo real de productos
        $this->call(RealProductsSeeder::class);
    }
}