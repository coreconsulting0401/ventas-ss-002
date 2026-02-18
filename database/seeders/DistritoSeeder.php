<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Distrito;

class DistritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFile = file_get_contents(database_path('datos/distritos.json'));
        $ubigeos = json_decode($jsonFile, true);

        foreach ($ubigeos as $ubigeoData) {
            Distrito::create([
                'nombre' => $ubigeoData['nombre'],
                'provincia_id' => $ubigeoData['provincia_id'],
            ]);
        }

        $this->command->info('Distritos sembradas!');
    }
}
