<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        // Solo crear si no existe ninguna empresa
        if (!Empresa::exists()) {
            Empresa::create([
                'razon_social' => 'FESEPSA S.A.',
                'ruc' => '20100004080',
                'direccion' => 'Av. Elmer Faucett T Nro.390 Urb. La colonial Prov. Const. Del Callao - Callao',
                'pagina_web' => 'https://www.fesepsa.com.pe',
                'uri_img_logo' => 'logos/1772051355_699f5b9b02c8a.png',
                'uri_img_publicidad' => 'publicidad/1772051355_699f5b9b08bd8.png',
                'uri_img_condiciones' => 'condiciones/1772051355_699f5b9b09441.png',
                'uri_cuentas_bancarias' => 'cuentas_bancarias/1772055036_699f69fc046a4.png',
            ]);

            $this->command->info('✅ Empresa creada exitosamente!');
        } else {
            $this->command->info('ℹ️  Ya existe una empresa registrada. Use el panel de administración para editar.');
        }
    }
}
