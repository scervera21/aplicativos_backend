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
        $permisos = Permission::all();

        return $this->successResponse('Roles obtenidos exitosamente', [
            'roles' => $roles,
            'permisos' => $permisos
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error en la validación', 422, $validator->errors());
        }

        $role = Role::create([
            'name' => $request->name
        ]);

        // Asignacion de los permisos

        $selected_permissions = [];

        foreach ($request->permissions as $permission) {
            $selected_permissions[] = Permission::where('name', $permission)->first()->id; // obtener el id del permiso por el nombre
        }

        //$permisos = Permission::whereIn('name', $request->permissions)->pluck('id')->toArray();

        if (!$selected_permissions) {
            return $this->errorResponse('No se pudieron obtener los permisos', 404);
        }

        $role->syncPermissions($selected_permissions);

        return $this->successResponse('Rol creado exitosamente', [
            'role' => $role,
            'permisos' => $selected_permissions // devuelve el array de ids de los permisos asignados y no los nombres de los permisos asignados a los roles
        ]);
    }


    public function update(Request $request, $roleId)
    {

        $role = Role::find($roleId);

        if (!$role) {
            return $this->errorResponse('Rol no encontrado', 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,     // Ignora el rol actual al verificar unicidad.
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error en la validación', 422, $validator->errors());
        }

        // ACTUALIZAR LOS PERMISOS

        $selected_permissions = [];

        foreach ($request->permissions as $permission) {
            $selected_permissions[] = Permission::where('name', $permission)->first()->id; // obtener el id del permiso por el nombre
        }

        //$permisos = Permission::whereIn('name', $request->permissions)->pluck('id')->toArray();

        if (!$selected_permissions) {
            return $this->errorResponse('No se pudieron obtener los permisos', 404);
        }

        $role->update([
            'name' => $request->name
        ]);

        $role->syncPermissions($selected_permissions);

        return $this->successResponse('Rol actualizado exitosamente', [
            'role' => $role,
            'permisos' => $selected_permissions
        ]);
    }

    public function destroy($roleId)
    {
        $role = Role::find($roleId);

        if (!$role) {
            return $this->errorResponse('Rol no encontrado', 404);
        }

        if ($role->users()->exists()) {
            return $this->errorResponse('No se puede eliminar el rol porque tiene usuarios asignados', 403);
        }

        $role->delete();

        return $this->successResponse('Rol eliminado exitosamente');
    }
}
