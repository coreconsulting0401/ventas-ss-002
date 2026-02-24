<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = ['Cotizado', 'Ganada', 'Perdida'];

        foreach ($estados as $estado) {
            DB::table('estados')->updateOrInsert(
                ['name' => $estado],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
