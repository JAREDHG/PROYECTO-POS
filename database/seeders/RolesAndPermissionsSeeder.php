<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la caché de Spatie antes de sembrar
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear los permisos guardando la instancia exacta en memoria
        $manageProducts = Permission::firstOrCreate(['name' => 'manage products', 'guard_name' => 'web']);
        $processSales = Permission::firstOrCreate(['name' => 'process sales', 'guard_name' => 'web']);

        // 2. Crear rol de Cajero y asignarle el objeto del permiso directamente
        $cashierRole = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashierRole->givePermissionTo($processSales);

        // 3. Crear rol de Administrador y asignarle el arreglo de objetos
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo([$manageProducts, $processSales]);
    }
}