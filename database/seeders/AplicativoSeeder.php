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
            $gsos = Gerencia::firstOrFail()->id;
        }

        if(!User::exists()) {
            $this->command->error('No existen usuarios registrados, por favor ejecuta UserSeeder primero.');
            return;
        } else {
            $users = User::all();
        }

        $aplicativos = [
            [
                'aplicativo'=> 'Sistema de Seguimiento de Aplicativos GGSI',
                'alias'=> null,
                'responsable'=> $users->find(1),
                'pap'=> false,
                'descripcion'=> 'Monitoreo de avances de los proyectos de la GGSI',
                'gerencia_id'=> $gsos,
            ],
            [
                'aplicativo'=> 'Sistema Integral Geolocalización e Incidencias GGSI',
                'alias'=> null,
                'responsable'=> $users->find(2),
                'pap'=> false,
                'descripcion'=> 'Centraliza y gestiona los reportes de incidencias entre las centrales CANTV',
                'gerencia_id'=> $gsos,
            ],       
        ];
        
        foreach ($aplicativos as $aplicativo) {
            Aplicativo::create($aplicativo);
        }
    }
}
