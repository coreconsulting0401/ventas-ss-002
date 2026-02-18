<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provincia;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFile = file_get_contents(database_path('datos/provincias.json'));
        $ubigeos = json_decode($jsonFile, true);

        foreach ($ubigeos as $ubigeoData) {
            Provincia::create([
                'nombre' => $ubigeoData['nombre'],
                'departamento_id' => $ubigeoData['departamento_id'],
            ]);
        }

        $this->command->info('Provincias sembradas!');
    }
}
