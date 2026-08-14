<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'Admin',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'password' => Hash::make('123456'),
                'status' => true,
            ]
        );
        $admin->assignRole(Role::findByName('Administrador', 'api'));

        // $supervisor = User::firstOrCreate(
        //     ['email' => 'supervisor@example.com'],
        //     [
        //         'username' => 'Supervisor',
        //         'first_name' => 'supervisor',
        //         'last_name' => 'supervisor',
        //         'password' => Hash::make('123456'),
        //         'status' => true,
        //     ]
        // );
        // $supervisor->assignRole(Role::findByName('Supervisor', 'api'));

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'username' => 'Usuario',
                'first_name' => 'usuario',
                'last_name' => 'usuario',
                'password' => Hash::make('123456'),
                'status' => true,
            ]
        );
        $user->assignRole(Role::findByName('Usuario', 'api'));
    }
}
