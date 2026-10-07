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
        if(!Aplicativo::exists()) {
            $this->command->error('No existen aplicativos registrados, por favor ejecuta AplicativoSeeder primero.');
            return;
        } 

        $sysaplicativos = Aplicativo::where('aplicativo', 'Sistema de Seguimiento de Aplicativos GGSI')->first();
        $geolocalizacion = Aplicativo::where('aplicativo', 'Sistema Integral Geolocalización e Incidencias GGSI')->first();

        if (!($sysaplicativos && $geolocalizacion)) {
            $this->command->error('Aplicativo no encontrado, revisa que AplicativoSeeder se haya ejecutado correctamente.');
            return;
        }

        $detalles = [
            [
                'aplicativo_id'=> $sysaplicativos->id,
                'lenguajes_frontend' => 'Vue 3',
                'lenguajes_backend' => 'Laravel 10',
                'bases_datos' => 'PostgreSQL 16',
                'observaciones' => 'En desarrollo',
            ],
            [
                'aplicativo_id'=> $geolocalizacion->id,
                'fecha_inicio'=> '2025-04-15',
                'fecha_fin'=> null,
                'lenguajes_frontend' => 'Vue 3',
                'lenguajes_backend' => 'Laravel 9',
                'bases_datos' => 'PostgreSQL 14',
                'observaciones' => 'En proceso de adecuación y pruebas QA',
            ],            
        ];

        foreach ($detalles as $detalle) {
            DetalleAplicativo::create($detalle);
        }
    }
}
