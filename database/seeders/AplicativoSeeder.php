<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aplicativo;
use App\Models\Gerencia;
use App\Models\User;

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
            $gsos = Gerencia::where('abreviacion', 'GSOS')->first();
        }

        if(!User::exists()) {
            $this->command->error('No existen usuarios registrados, por favor ejecuta UserSeeder primero.');
            return;
        } else {
            $usuarios = User::all();
        }

        $aplicativos = [
            [
                'nombre_aplicativo'=> 'Sistema de Seguimiento de Aplicativos GGSI',
                'abreviacion'=> null,
                'descripcion'=> 'Monitoreo de avances de los proyectos de la GGSI',
                'responsable'=> $usuarios->find(1)->id,
                'pap'=> false,
                'estatus'=> null,
                'gerencia_linea'=> $gsos->id,
            ],
            [
                'nombre_aplicativo'=> 'Sistema Integral Geolocalización e Incidencias GGSI',
                'abreviacion'=> null,
                'descripcion'=> 'Centraliza y gestiona los reportes de incidencias entre las centrales CANTV',
                'responsable'=> $usuarios->find(2)->id,
                'pap'=> false,
                'estatus'=> null,
                'gerencia_linea'=> $gsos->id,
            ],       
        ];
        
        foreach ($aplicativos as $aplicativo) {
            Aplicativo::create($aplicativo);
        }
    }
}
