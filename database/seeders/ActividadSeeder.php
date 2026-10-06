<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Actividad;
use App\Models\Aplicativo;

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

        $sysaplicativos = Aplicativo::where('nombre_aplicativo', 'Sistema de Seguimiento de Aplicativos GGSI')->first();
        $geolocalizacion = Aplicativo::where('nombre_aplicativo', 'Sistema Integral Geolocalización e Incidencias GGSI')->first();

        if (!($sysaplicativos && $geolocalizacion)) {
            $this->command->error('Aplicativo no encontrado, revisa que AplicativoSeeder se haya ejecutado correctamente.');
            return;
        }

        $actividades = [
            [
                'actividad' => 'Mapeo de base de datos y carga de datos',
                'creado_el' => '2026-10-05',
                'culminado_el' => null,
                'area' => 'Base de Datos',
                'prioridad' => 'Alta',
                'colaboradores' => null,
                'completado' => false,
                'aplicativo_id' => $sysaplicativos->id,
            ],
            [
                'actividad' => 'Elaboracion de diagrama dinamico de niveles de riesgo',
                'creado_el' => '2026-10-02',
                'culminado_el' => '2026-10-03',
                'area'=> 'Desarrollo',
                'prioridad'=> 'Alta',
                'colaboradores' => ['GSOS','CROS'],
                'completado' => true,
                'aplicativo_id'=> $geolocalizacion->id,
            ],
        ];

        foreach ($actividades as $actividad) {
            Actividad::firstOrCreate($actividad);
        }
    }
}
