<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DescuentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('descuentos')->updateOrInsert(
            ['id' => 1], // Buscamos por el ID 1
            [
                'porcentaje' => 15.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
