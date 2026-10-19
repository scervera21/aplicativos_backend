<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::with('permissions')->get();

        return $this->successResponse('Roles obtenidos exitosamente', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:security.roles|uppercase',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:security.permissions',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error en la validación', 422, $validator->errors());
        }

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        return $this->successResponse('Rol creado exitosamente', [
            'role' => $role,
        ]);
    }


    public function update(Request $request, Role $roleId)
    {
        $role = Role::find($roleId)->load('permissions');

        if (!$role) {
            return $this->errorResponse('Rol no encontrado', 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:security.roles' . $role . '|uppercase',     // Ignora el rol actual al verificar unicidad.
            'permissions' => 'required|array',
            'permissions.*' => 'exists:security.permissions,name',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error en la validación', 422, $validator->errors());
        }

        $role->update([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        return $this->successResponse('Rol actualizado exitosamente', [
            'role' => $role,
        ]);
    }

    public function destroy($roleId)
    {
        $role = Role::find($roleId);

        if (!$role) {
            return $this->errorResponse('Rol no encontrado', 404);
        } elseif (auth()->user()->hasRole($role->name)) {
            return $this->errorResponse('No puedes eliminar tu propio rol', 403);
        } else {
            $role->delete();
            return $this->successResponse('Rol eliminado exitosamente');
        }
    }
}
