<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['name' => 'Contado', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Contra entrega', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Crédito', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('transaccions')->insert($tipos);
    }
}
