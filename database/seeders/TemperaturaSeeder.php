<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemperaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $temperaturas = [
            ['name' => '20%', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '50%', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '70%', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('temperaturas')->insert($temperaturas);
    }
}
