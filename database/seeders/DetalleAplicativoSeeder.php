<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetalleAplicativo;
use App\Models\Aplicativo;

class DetalleAplicativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sysaplicativos = Aplicativo::where('nombre_aplicativo', 'Sistema de Seguimiento de Aplicativos GGSI')->first();
        $geolocalizacion = Aplicativo::where('nombre_aplicativo', 'Sistema Integral Geolocalización e Incidencias GGSI')->first();

        if (!($sysaplicativos && $geolocalizacion)) {
            $this->command->error('Aplicativo no encontrado, revisa que AplicativoSeeder se haya ejecutado correctamente.');
            return;
        }

        $detalles = [
            [
                'aplicativo_id'=> $sysaplicativos->id,
                'fecha_inicio'=> '2026-04-20',
                'fecha_fin'=> null,
                'lenguajes_frontend' => 'Vue 3',
                'lenguajes_backend' => 'Laravel 10',
                'base_datos' => 'PostgreSQL 16',
                'observaciones' => 'En desarrollo',
            ],
            [
                'aplicativo_id'=> $geolocalizacion->id,
                'fecha_inicio'=> '2025-04-15',
                'fecha_fin'=> null,
                'lenguajes_frontend' => 'Vue 3',
                'lenguajes_backend' => 'Laravel 9',
                'base_datos' => 'PostgreSQL 14',
                'observaciones' => 'En proceso de adecuación y pruebas QA',
            ],            
        ];

        foreach ($detalles as $detalle) {
            DetalleAplicativo::create($detalle);
        }
    }
}
