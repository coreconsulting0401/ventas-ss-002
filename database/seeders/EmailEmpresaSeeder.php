<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailEmpresa;

class EmailEmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $emails = [
            [
                'id' => 1,
                'empresa_id' => 1,
                'area' => 'Ventas',
                'email' => 'ventas@fesepsa.pe',
                'activo' => true
            ],
            [
                'id' => 2,
                'empresa_id' => 1,
                'area' => 'Servicio de Calibración',
                'email' => 'calibracion@fesepsa.pe',
                'activo' => true
            ],
            [
                'id' => 3,
                'empresa_id' => 1,
                'area' => 'Servicio Técnico',
                'email' => 'serviciotecnico@fesepsa.pe',
                'activo' => true
            ],
            [
                'id' => 4,
                'empresa_id' => 1,
                'area' => 'Cobranzas',
                'email' => 'cobranza@fesepsa.pe',
                'activo' => true
            ],
            [
                'id' => 5,
                'empresa_id' => 1,
                'area' => 'Comercial',
                'email' => 'comercialfesepsa@fesepsa.pe',
                'activo' => true
            ],
        ];

        foreach ($emails as $data) {
            EmailEmpresa::updateOrCreate(['id' => $data['id']], $data);
        }
    }
}
