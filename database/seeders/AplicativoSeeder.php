<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aplicativo;
use App\Models\Gerencia;

class AplicativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Consultamos la existencia de gerencias y usuarios antes de crear los aplicativos
        
        if(!Gerencia::exists()) {
            $this->command->error('No existen gerencias registradas, por favor ejecuta GerenciaSeeder primero.');
            return;
        } else {
            $gerencia = Gerencia::first();
        }

        $aplicativos = [
            [
                'nombre_aplicativo'=> 'Sistema de Seguimiento de Aplicativos GGSI',
                'alias'=> null,
                'pap'=> false,
                'descripcion'=> 'Monitoreo de avances de los proyectos de la GGSI',
                'gerencia_id'=> $gerencia->id,
                'lenguajes_frontend' => 'VUE3',
                'lenguajes_backend' => 'LARAVEL 9',
                'bases_datos' => 'PostgreSQL 16',
                'fecha_inicio' => '2025-01-01',
                'fecha_fin' => '2025-11-01',
            ],
            [
                'nombre_aplicativo'=> 'Sistema Integral Geolocalización e Incidencias GGSI',
                'alias'=> 'SIGI',
                'pap'=> true,
                'estatus'=> 'Implementado',
                'responsable'=> 'Luis Sarmiento',
                'gerencia_id'=> $gerencia->id,
                'lenguajes_backend' => 'LARAVEL 9',
                'bases_datos' => 'PostgreSQL/MongoDB',
                'fecha_inicio' => '2019-06-01',
                'fecha_fin' => '2026-08-01',
            ],       
        ];
        
        foreach ($aplicativos as $aplicativo) {
            Aplicativo::firstOrCreate($aplicativo);
        }
    }
}
