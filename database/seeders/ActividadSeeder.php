<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Actividad;
use App\Models\Aplicativo;
use App\Models\Area;
use App\Models\Gerencia;

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


        $aplicativo = Aplicativo::where('nombre_aplicativo', 'Sistema de Seguimiento de Aplicativos GGSI')->first();
        $areaLevantamiento = Area::where('nombre_area', 'LEVANTAMIENTO DE INFORMACION')->first();
        $areaDesarrollo = Area::where('nombre_area', 'DESARROLLO')->first();
        $gerenciaColab = Gerencia::skip(1)->first() ?? Gerencia::first();

        if ($aplicativo && $areaLevantamiento) {
            $actividad1 = Actividad::firstOrCreate([
                'nombre_actividad' => 'Levantamiento inicial de requerimientos',
                'aplicativo_id' => $aplicativo->id,
                'area_id' => $areaLevantamiento->id,
                'prioridad' => 'BAJA',
                'porcentaje' => 90,
                'fecha_creacion' => '2026-02-18',
                'fecha_culminacion' => '2026-03-18',
                'estado' => 'ATRASADO',
                'comentarios' => 'Requerimientos aprobados'
            ]);

            // Asignar gerencia colaboradora
            if ($gerenciaColab) {
                $actividad1->gerenciasColaboradoras()->sync([$gerenciaColab->id]);
            }
        }

        if ($aplicativo && $areaDesarrollo) {
            Actividad::firstOrCreate([
                'nombre_actividad' => 'Desarrollo de módulos',
                'aplicativo_id' => $aplicativo->id,
                'area_id' => $areaDesarrollo->id,
                'prioridad' => 'ALTA',
                'porcentaje' => 60,
                'fecha_creacion' => '2026-02-23',
                'fecha_culminacion' => null,
                'estado' => 'PENDIENTE',
                'comentarios' => 'En proceso de codificación'
            ]);
        }
    }
}
