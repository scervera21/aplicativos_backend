<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Gerencia;
use Illuminate\Support\Facades\Hash;

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
            $gerencia = Gerencia::firstOrFail()->id;
        }

        if(!Role::exists()) {
            $this->command->error('No existen roles registrados, por favor ejecuta RoleSeeder primero.');
            return;
        } else {
            $admin_role = Role::where('name', 'administrador')->get();
            $user_role = Role::where('name', 'usuario')->get();
        }

        $admin = User::firstOrCreate(
            ['username' => 'admin'],

            [
                'username' => 'admin',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@cantv.com',
                'password' => Hash::make('admin123'),
                'gerencia_id' => $gerencia,
            ]
        );

        $admin->assignRole($admin_role);

        $user = User::firstOrCreate(
            ['username' => 'test_user'],

            [
                'username' => 'test_user',
                'first_name' => 'test_user',
                'last_name' => 'test_user',
                'email' => 'test_user@cantv.com',
                'password' => Hash::make('testuser'),
                'gerencia_id' => $gerencia,
            ]
        );
        $user->assignRole($user_role);
    }
}
