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
            ['username' => 'admin'],

            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@cantv.com',
                'telefono' => '04141234567',
                'password' => Hash::make('admin123'),
                'status' => true,
            ]
        );

        $admin->assignRole(Role::findByName('administrador', 'api'));

        $user = User::firstOrCreate(
            ['email' => 'invitado@cantv.com.ve'],
            [
                'username' => 'invitado',
                'first_name' => 'invitado',
                'last_name' => 'invitado',
                'password' => Hash::make('invitado'),
                'status' => true,
            ]
        );
        $user->assignRole(Role::findByName('usuario', 'api'));
    }
}
