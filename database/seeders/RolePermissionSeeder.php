<?php

/**
 * SEEDER: RolePermissionSeeder.php
 * Ubicación: database/seeders/RolePermissionSeeder.php
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos para cada módulo
        $modules = [
            'contactos',
            'clientes',
            'creditos',
            'categorias',
            'direcciones',
            'productos',
            'descuentos',
            'proformas',
            'transacciones',
            'temperaturas',
            'estados',
            'virtuals',
            'proveedores',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::create(['name' => "$action $module"]);
            }
        }

        // Crear roles
        $adminRole = Role::create(['name' => 'Administrador']);
        $vendedorRole = Role::create(['name' => 'Vendedor']);
        $almacenRole = Role::create(['name' => 'Almacén']);
        $visualizadorRole = Role::create(['name' => 'Visualizador']);

        // Asignar todos los permisos al Administrador
        $adminRole->givePermissionTo(Permission::all());

        // Permisos para Vendedor (puede gestionar clientes, contactos y proformas)
        $vendedorPermissions = [
            'view contactos', 'create contactos', 'edit contactos',
            'view clientes', 'create clientes', 'edit clientes',
            'view proformas', 'create proformas', 'edit proformas',
            'view productos', 'view virtuals',
            'view transacciones', 'view temperaturas', 'view estados',
            'view categorias', 'view direcciones',
        ];
        $vendedorRole->givePermissionTo($vendedorPermissions);

        // Permisos para Almacén (puede gestionar productos, virtuals y proveedores)
        $almacenPermissions = [
            'view productos', 'create productos', 'edit productos', 'delete productos',
            'view virtuals', 'create virtuals', 'edit virtuals', 'delete virtuals',
            'view proveedores', 'create proveedores', 'edit proveedores', 'delete proveedores',
            'view descuentos', 'create descuentos', 'edit descuentos',
        ];
        $almacenRole->givePermissionTo($almacenPermissions);

        // Permisos para Visualizador (solo ver)
        $visualizadorPermissions = Permission::where('name', 'like', 'view%')->pluck('name');
        $visualizadorRole->givePermissionTo($visualizadorPermissions);

        // Crear usuario administrador por defecto
        $admin = User::create([
            'name' => 'Administrador Sistema',
            'dni' => '12345678',
            'email' => 'admin@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'ADM-001',
        ]);
        $admin->assignRole('Administrador');

        // Crear usuario vendedor por defecto
        $vendedor = User::create([
            'name' => 'Juan Vendedor',
            'dni' => '87654321',
            'email' => 'vendedor@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'VEN-001',
        ]);
        $vendedor->assignRole('Vendedor');

        // Crear usuario almacén por defecto
        $almacenero = User::create([
            'name' => 'Pedro Almacén',
            'dni' => '11223344',
            'email' => 'almacen@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'ALM-001',
        ]);
        $almacenero->assignRole('Almacén');

        $this->command->info('Roles y permisos creados exitosamente!');
        $this->command->info('Usuario Admin: admin@proformas.com / password');
        $this->command->info('Usuario Vendedor: vendedor@proformas.com / password');
        $this->command->info('Usuario Almacén: almacen@proformas.com / password');
    }
}
