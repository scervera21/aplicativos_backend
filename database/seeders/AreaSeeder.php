<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['nombre_area' => 'LEVANTAMIENTO DE INFORMACION'],
            ['nombre_area' => 'BASE DE DATOS'],
            ['nombre_area' => 'DESARROLLO'],
            ['nombre_area' => 'INFRAESTRUCTURA'],
        ];

        foreach ($areas as $area) {
            Area::firstOrCreate($area);
        }
    }
}
