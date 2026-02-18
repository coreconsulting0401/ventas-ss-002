<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFile = file_get_contents(database_path('datos/departamentos.json'));
        $ubigeos = json_decode($jsonFile, true);

        foreach ($ubigeos as $ubigeoData) {
            Departamento::create([
                'nombre' => $ubigeoData['nombre'],
            ]);
        }

        $this->command->info('Departamentos sembrados!');
    }
}
