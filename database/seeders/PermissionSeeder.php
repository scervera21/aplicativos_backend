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

        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission . '_' . $module,
                    'guard_name' => 'api',
                    'category' => $permission == 'ver' ? 'access' : 'action',
                    'module' => $module
                ]);
            }
        }
    }
}
