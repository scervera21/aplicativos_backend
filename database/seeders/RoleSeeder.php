<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if(!Permission::exists()) {
            $this->command->error('No existen permisos registrados, por favor ejecuta PermissionSeeder primero.');
            return;
        }

        // Rol Administrador
        $admin_role = Role::firstOrCreate([
            'name' => 'administrador',
            'guard_name' => 'api',
        ]);

        $admin_role->syncPermissions(Permission::all());
        
        // Rol Usuario
        
        $regular_user_role = Role::firstOrCreate([
            'name' => 'usuario',
            'guard_name' => 'api',
        ]);

        $regular_user_role->syncPermissions([
            'ver_dashboard',
            'ver_aplicativos',
            'ver_detalles_aplicativos',
        ]);
    }
}
