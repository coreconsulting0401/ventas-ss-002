<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir las categorías básicas
        $categorias = [
            [
                'name' => 'Minorista',
            ],
            [
                'name' => 'Usuario Final',
            ],
        ];

        // Insertar los registros
        foreach ($categorias as $categoriaData) {
            Categoria::create($categoriaData);
        }

        // Mensaje de confirmación
        $this->command->info('✓ Seeder de Categorías ejecutado correctamente.');
        $this->command->info('  - Se crearon 2 registros de categorías:');
        $this->command->info('    • ID 1: Minorista');
        $this->command->info('    • ID 2: Usuario Final');
    }
}
