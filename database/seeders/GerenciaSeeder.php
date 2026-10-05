<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gerencia;

class GerenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gerencias = [
            [
                'nombre_gerencia'=>'Gerencia Seguridad, Operacion y Servicios',
                'abreviacion'=>'GSOS',
            ],
        ];

        foreach ($gerencias as $gerencia) {
            Gerencia::create($gerencia);
        }
    }
}
