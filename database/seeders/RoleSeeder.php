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
        $admin_role = Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'api',
        ]);

        $admin_role->syncPermissions(Permission::all());

        // $supervisor_role = Role::firstOrCreate([
        //     'name' => 'Supervisor',
        //     'guard_name' => 'api',
        // ]);

        // $permissions_supervisor = Permission::where('category', 'access')->orWhere('module', 'aplicativos')->get();
        // $supervisor_role->syncPermissions($permissions_supervisor);
        
        $regular_user_role = Role::firstOrCreate([
            'name' => 'Usuario',
            'guard_name' => 'api',
        ]);

        $permissions_user = Permission::where('module', 'dashboard')->get();
        $regular_user_role->syncPermissions($permissions_user);
    }
}
