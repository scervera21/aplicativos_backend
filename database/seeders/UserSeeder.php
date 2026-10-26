<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Gerencia;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if(!Gerencia::exists()) {
            $this->command->error('No existen gerencias registradas, por favor ejecuta GerenciaSeeder primero.');
            return;
        } else {
            // Obtenemos una gerencia por defecto para asociar los usuarios
            $gerenciaDefault = Gerencia::first();
        }

        if(!Role::exists()) {
            $this->command->error('No existen roles registrados, por favor ejecuta RoleSeeder primero.');
            return;
        } else {
            $admin_role = Role::where('name', 'administrador')->first();
            $colaborador_role = Role::where('name', 'colaborador')->first();
        }

        $admin = User::firstOrCreate(
            ['username' => 'Admin User'],
            [
                'username' => 'admin',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@cantv.com',
                'password' => bcrypt('password'),
                'gerencia_id' => $gerenciaDefault->id,
            ]
        );

        $admin->assignRole($admin_role);

        $user = User::firstOrCreate(
            ['username' => 'Colaborador User'],
            [
                'username' => 'colaborador',
                'first_name' => 'colaborador',
                'last_name' => 'colaborador',
                'email' => 'colaborador@cantv.com',
                'password' => bcrypt('password'),
                'gerencia_id' => $gerenciaDefault->id,
            ]
        );

        $user->assignRole($colaborador_role);
    }
}
