<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TelefonoEmpresa;

class TelefonoEmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $telefonos = [
            [
                'id' => 1,
                'empresa_id' => 1,
                'area' => 'Oficina Principal',
                'telefono' => '4511052',
                'descripcion' => 'Oficina principal',
                'activo' => true
            ],
            [
                'id' => 2,
                'empresa_id' => 1,
                'area' => 'Oficina Principal',
                'telefono' => '4514787',
                'descripcion' => 'Oficina principal',
                'activo' => true
            ],
            [
                'id' => 3,
                'empresa_id' => 1,
                'area' => 'Servicio de Calibración',
                'telefono' => '954714023',
                'descripcion' => 'Oficina principal',
                'activo' => true
            ],
        ];

        foreach ($telefonos as $data) {
            TelefonoEmpresa::updateOrCreate(['id' => $data['id']], $data);
        }
    }
}
