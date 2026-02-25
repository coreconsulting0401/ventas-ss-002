<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([

            // Ubigeo — orden obligatorio por claves foráneas
            DepartamentoSeeder::class,
            ProvinciaSeeder::class,
            DistritoSeeder::class,

            // Otros
            DescuentoSeeder::class,
            CategoriaSeeder::class,
            CreditoSeeder::class,
            TransaccionSeeder::class,
            EstadoSeeder::class,
            TemperaturaSeeder::class,
            IgvSeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
