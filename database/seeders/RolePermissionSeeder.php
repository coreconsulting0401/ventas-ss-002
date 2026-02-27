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
            //'virtuals',
            //'proveedores',
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
        $AdminRole = Role::create(['name' => 'Administrador']);
        $GerenteRole = Role::create(['name' => 'Gerente']);
        $vendedorRole = Role::create(['name' => 'Vendedor']);
        $almacenRole = Role::create(['name' => 'Almacén']);
        $exrabajadorRole = Role::create(['name' => 'Extrabajador']);

        // sin permisos para extrabajador, solo acceso a su perfil


        // Administrador: TODOS los permisos
        $AdminRole->givePermissionTo(Permission::all());

        // Vendedor: gestión de clientes, contactos y proformas
        $vendedorPermissions = [
            'view contactos', 'create contactos', 'edit contactos',
            'view clientes', 'create clientes', 'edit clientes',
            'view proformas', 'create proformas', 'edit proformas',
            'view productos', //'view virtuals',
            'view transacciones', 'view temperaturas', 'view estados',
            'view categorias', 'view direcciones',
        ];
        $vendedorRole->givePermissionTo($vendedorPermissions);

        // Almacén: gestión de productos, virtuals y proveedores
        $almacenPermissions = [
            'view productos', 'create productos', 'edit productos', 'delete productos',
            //'view virtuals', 'create virtuals', 'edit virtuals', 'delete virtuals',
            //'view proveedores', 'create proveedores', 'edit proveedores', 'delete proveedores',
            'view descuentos', 'create descuentos', 'edit descuentos',
        ];
        $almacenRole->givePermissionTo($almacenPermissions);

        // Gerente: solo ver todo
        $GerentePermissions = [
            //todos los permisos pra cada modulo menos el permiso delete

            'view contactos','create contactos','edit contactos',
            'view clientes', 'create clientes', 'edit clientes',
            'view proformas', 'create proformas', 'edit proformas',
            'view productos','create productos','edit productos',
             //'view virtuals','create virtuals','edit virtuals',
             //'view proveedores','create proveedores','edit proveedores',
            'view descuentos','create descuentos','edit descuentos',
            'view transacciones', 'create transacciones', 'edit transacciones',
            'view temperaturas', 'create temperaturas', 'edit temperaturas',
            'view estados', 'create estados', 'edit estados',
            'view categorias', 'create categorias', 'edit categorias',
            'view direcciones', 'create direcciones', 'edit direcciones',
            'view users', 'create users', 'edit users',
            'view roles', //'create roles', 'edit roles',
            'view cambios', 'create cambios', 'edit cambios', 'delete cambios',
            //'view empresas',  'edit empresas',
            'view creditos', 'create creditos', 'edit creditos', 'delete creditos',
        ];

        $GerenteRole->givePermissionTo($GerentePermissions);

        $exrabajadorRole->givePermissionTo('view users');

        // Crear usuarios de ejemplo
        $Admin = User::create([
            'name' => 'Luis Alberto Cusy Ricci',
            'dni' => '42969452',
            'email' => 'dacluis7@gmail.com',
            'password' => Hash::make('Luis1313155'),
            'codigo' => 'ADM-001',
        ]);
        $Admin->assignRole('Administrador');

        // Crear usuarios de ejemplo
        $gerente = User::create([
            'name' => 'Juan J. Gerente',
            'dni' => '12345678',
            'email' => 'gerente@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'GER-001',
        ]);
        $gerente->assignRole('Gerente');


        $vendedor = User::create([
            'name' => 'Juan J.Vendedor',
            'dni' => '87654321',
            'email' => 'vendedor@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'VEN-001',
        ]);
        $vendedor->assignRole('Vendedor');

        $almacenero = User::create([
            'name' => 'Pedro P.Almacén',
            'dni' => '11223344',
            'email' => 'almacen@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'ALM-001',
        ]);
        $almacenero->assignRole('Almacén');

        $extrabajador = User::create([
            'name' => 'Pedro P.Extrabajador',
            'dni' => '11223644',
            'email' => 'extrabajador@proformas.com',
            'password' => Hash::make('password'),
            'codigo' => 'EXT-001',
        ]);
        $extrabajador->assignRole('Extrabajador');



        $this->command->info('✅ Roles y permisos creados exitosamente!');
        $this->command->info('📧 Usuario Administrador del sistema: dacluis7@gmail.com / Luis1313155');
        $this->command->info('📧 Usuario Gerente: gerente@proformas.com / password');
        $this->command->info('📧 Usuario Vendedor: vendedor@proformas.com / password');
        $this->command->info('📧 Usuario Almacén: almacen@proformas.com / password');
    }
}
