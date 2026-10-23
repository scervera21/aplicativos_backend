<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function index()
    {
        try {
            $roles = Role::with('permissions')->get();

            return response()->json([
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:security.roles|uppercase',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:security.permissions',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Error en la validación', 422, $validator->errors());
            }

            $role = Role::create([
                'name' => $request->name
            ]);

            if ($request->permissions) {
                $role->permissions()->sync($request->permissions);
            }

            return response()->json([
                'message' => 'Rol creado exitosamente',
                'role' => $role,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $roleId)
    {
        try {

            $role = Role::find($roleId)->load('permissions');

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:security.roles' . $role . '|uppercase',     // Ignora el rol actual al verificar unicidad.
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:security.permissions,name',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error en la validación',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $role->update([
                'name' => $request->name
            ]);

            if ($request->permissions) {
                $role->permissions()->sync($request->permissions);
            }

            return response()->json([
                'message' => 'Rol actualizado exitosamente',
                'role' => $role,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($roleId)
    {
        try {
            $role = Role::find($roleId);

            if (!$role) {
                return $this->errorResponse('Rol no encontrado', 404);
            } else if (auth()->user()->hasRole($role->name)) {
                return $this->errorResponse('No puedes eliminar tu propio rol', 403);
            } else {
                $role->delete();
                return $this->successResponse('Rol eliminado exitosamente');
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
