<?php

/**
 * SEEDER: RolePermissionSeeder.php (ACTUALIZADO)
 * Ubicación: database/seeders/RolePermissionSeeder.php
 *
 * REEMPLAZAR EL ARCHIVO COMPLETO con este contenido
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Módulos del sistema
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
            'users',
            'roles',
            'cambios',
            'empresas',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        // Crear permisos
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

        // Administrador: TODOS los permisos
        $adminRole->givePermissionTo(Permission::all());

        // Vendedor: gestión de clientes, contactos y proformas
        $vendedorPermissions = [
            'view contactos', 'create contactos', 'edit contactos',
            'view clientes', 'create clientes', 'edit clientes',
            'view proformas', 'create proformas', 'edit proformas',
            'view productos', 'view virtuals',
            'view transacciones', 'view temperaturas', 'view estados',
            'view categorias', 'view direcciones',
        ];
        $vendedorRole->givePermissionTo($vendedorPermissions);

        // Almacén: gestión de productos, virtuals y proveedores
        $almacenPermissions = [
            'view productos', 'create productos', 'edit productos', 'delete productos',
            'view virtuals', 'create virtuals', 'edit virtuals', 'delete virtuals',
            'view proveedores', 'create proveedores', 'edit proveedores', 'delete proveedores',
            'view descuentos', 'create descuentos', 'edit descuentos',
        ];
        $almacenRole->givePermissionTo($almacenPermissions);

        // Visualizador: solo ver todo
        $visualizadorPermissions = Permission::where('name', 'like', 'view%')->pluck('name');
        $visualizadorRole->givePermissionTo($visualizadorPermissions);

        // Crear usuarios de ejemplo
        $admin = User::create([
            'name' => 'Administrador Sistema',
            'dni' => '12345678',
            'email' => 'admin@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'ADM-001',
        ]);
        $admin->assignRole('Administrador');

        $vendedor = User::create([
            'name' => 'Juan Vendedor',
            'dni' => '87654321',
            'email' => 'vendedor@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'VEN-001',
        ]);
        $vendedor->assignRole('Vendedor');

        $almacenero = User::create([
            'name' => 'Pedro Almacén',
            'dni' => '11223344',
            'email' => 'almacen@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'ALM-001',
        ]);
        $almacenero->assignRole('Almacén');

        $this->command->info('✅ Roles y permisos creados exitosamente!');
        $this->command->info('📧 Usuario Admin: admin@proformas.com / password');
        $this->command->info('📧 Usuario Vendedor: vendedor@proformas.com / password');
        $this->command->info('📧 Usuario Almacén: almacen@proformas.com / password');
    }
}
