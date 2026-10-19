<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Listado de módulos y sus permisos
        $modules = [
            'dashboard' => ['ver'],
            'gerencias' => ['ver', 'crear', 'editar', 'eliminar'],
            'usuarios' => ['ver', 'crear', 'editar', 'eliminar'],
            'roles' => ['ver', 'crear', 'editar', 'eliminar'],
            'permisos' => ['ver', 'crear', 'editar', 'eliminar'],
        ];

        foreach ($modules as $module => $permissions) {     // $variable => $value
            // Itera sobre los módulos y sus permisos ($module => 'nombre del módulo' | $permissions => 'array de permisos')             
            foreach ($permissions as $permission) {             
                // Itera sobre los permisos ($permission => 'nombre del permiso')
                Permission::firstOrCreate([
                    'name' => $permission . '.' . $module,
                    'guard_name' => 'api',
                    'module' => $module
                ]);
            }
        }
    }
}
