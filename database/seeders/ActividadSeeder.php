<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Actividad;
use App\Models\Aplicativo;
use App\Models\Area;

class ActividadSeeder extends Seeder
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
        
        if(!Area::exists()) {
            $this->command->error('No existen areas registradas, por favor ejecuta AreaSeeder primero.');
            return;
        }


        $sysaplicativos = Aplicativo::where('aplicativo', 'Sistema de Seguimiento de Aplicativos GGSI')->first()->id;
        $geolocalizacion = Aplicativo::where('aplicativo', 'Sistema Integral Geolocalización e Incidencias GGSI')->first()->id;
        $areadesarrollo = Area::where('nombre_area', 'Desarrollo')->first()->id;
        $areabd = Area::where('nombre_area', 'Base de Datos')->first()->id;

        if (!($sysaplicativos && $geolocalizacion && $areadesarrollo && $areabd)) {
            $this->command->error('Aplicativo no encontrado, revisa que AplicativoSeeder se haya ejecutado correctamente.');
            return;
        }

        $actividades = [
            [
                'actividad' => 'Mapeo de base de datos y carga de datos',
                'prioridad' => 'Alta',
                'completado' => false,
                'aplicativo_id' => $sysaplicativos,
                'area_id' => $areabd,
            ],
            [
                'actividad' => 'Rediseño de menu dinamico de reportes de incidencias',
                'prioridad'=> 'Alta',
                'estado' => 'Finalizado',
                'completado' => true,
                'aplicativo_id'=> $geolocalizacion,
                'area_id' => $areadesarrollo,
            ],
        ];

        foreach ($actividades as $actividad) {
            Actividad::firstOrCreate($actividad);
        }
    }
}
