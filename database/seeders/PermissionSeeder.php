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

        // Limpia la caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Listado de módulos y sus permisos
        $modules = [
            'dashboard' => ['ver'],
            'usuarios' => ['ver','gestionar'],
            'roles' => ['ver','gestionar'],
            'aplicativos' => ['ver','gestionar'],
            'actividades' => ['ver','gestionar'],
        ];

        foreach ($modules as $module => $permissions) {     // $variable => $value
            // Itera sobre los módulos y sus permisos ($module => 'nombre del módulo' | $permissions => 'array de permisos')             
            foreach ($permissions as $permission) {             
                // Itera sobre los permisos ($permission => 'nombre del permiso')
                Permission::firstOrCreate([
                    'name' => $permission . '.' . $module,
                    'guard_name' => 'api',
                ]);
            }
        }
    }
}
