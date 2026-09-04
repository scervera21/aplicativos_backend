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
            ['email' => 'scerve01@cantv.com.ve'],
            [
                'username' => 'scerve01',
                'first_name' => 'sara',
                'last_name' => 'cervera',
                'password' => Hash::make('30926047'),
                'status' => true,
            ]
        );
        $admin->assignRole(Role::findByName('administrador', 'api'));

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
            ['email' => 'snaumann01@cantv.com.ve'],
            [
                'username' => 'snaumann01',
                'first_name' => 'stephany',
                'last_name' => 'naumann',
                'password' => Hash::make('123456'),
                'status' => true,
            ]
        );
        $user->assignRole(Role::findByName('usuario', 'api'));
    }
}
