<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        foreach ($roles as $role) {
            $role->permission_names = $role->permissions()->pluck('name')->toArray();
        }

        return $this->successResponse('Roles obtenidos exitosamente', [
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'permission_names' => 'required|array',
            'permission_names.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error en la validación', 422, $validator->errors());
        }

        $role = Role::create([
            'name' => $request->name
        ]);

        // Asignacion de los permisos

        $permission_ids = Permission::whereIn('name', $request->permission_names)->pluck('id')->toArray();

        if (!$permission_ids) {
            return $this->errorResponse('No se pudieron obtener los permisos', 404);
        }

        $role->syncPermissions($permission_ids);

        return $this->successResponse('Rol creado exitosamente', [
            'role' => $role,
            'permission_names' => $request->permission_names
        ]);
    }


    public function update(Request $request, Role $roleId)
    {

        $role = Role::findOrFail($roleId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,     // Ignora el rol actual al verificar unicidad.
            'permission_names' => 'required|array',
            'permission_names.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error en la validación', 422, $validator->errors());
        }

        $role->update([
            'name' => $request->name
        ])->syncPermissions($request->permission_names);

        return $this->successResponse('Rol actualizado exitosamente', [
            'role' => $role,
            'permission_names' => $request->permission_names
        ]);
    }

    public function destroy($roleId)
    {
        $role = Role::findOrFail($roleId);

        if ($role->users()->exists()) {
            return $this->errorResponse('No se puede eliminar el rol porque tiene usuarios asignados', 403);
        }

        $role->delete();

        return $this->successResponse('Rol eliminado exitosamente');
    }
}
