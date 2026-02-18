<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Credito;

class CreditoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir los estados de crédito básicos
        $creditos = [
            [
                'aprobacion' => true,
            ],
            [
                'aprobacion' => false,
            ],
        ];

        // Insertar los registros
        foreach ($creditos as $creditoData) {
            Credito::create($creditoData);
        }

        // Mensaje de confirmación
        $this->command->info('✓ Seeder de Créditos ejecutado correctamente.');
        $this->command->info('  - Se crearon 2 registros de crédito:');
        $this->command->info('    • ID 1: Aprobado (true)');
        $this->command->info('    • ID 2: Desaprobado (false)');
    }
}
