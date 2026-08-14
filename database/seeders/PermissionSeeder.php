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
            'dashboard' => ['acceder'],
            'aplicativos' => ['acceder', 'crear', 'editar', 'eliminar'],
            'usuarios' => ['acceder', 'crear', 'editar', 'eliminar', 'asignar_roles'],
            'roles' => ['acceder', 'crear', 'editar', 'eliminar', 'asignar_permisos'],
            'permisos' => ['acceder', 'crear', 'editar', 'eliminar', 'asignar_roles'],
        ];

        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission . '_' . $module,
                    'guard_name' => 'api',
                    'category' => $permission == 'acceder' ? 'access' : 'action',
                    'module' => $module
                ]);
            }
        }
    }
}
