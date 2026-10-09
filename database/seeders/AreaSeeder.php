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
        Area::create(['nombre_area' => 'Desarrollo de Software']);
        Area::create(['nombre_area' => 'Infraestructura']);
        Area::create(['nombre_area' => 'Bases de Datos']);
        Area::create(['nombre_area' => 'Levantamiento de Informacion']);
    }
}
