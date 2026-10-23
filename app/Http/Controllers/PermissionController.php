<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    public function index()
    {
        try {
            $permissions = Permission::all();
            return response()->json([
                'permissions' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los permisos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:security.permissions',
                'category' => 'required|string',
                'module' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error en la validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $permission = Permission::create($request->all());
            return response()->json([
                'message' => 'Permiso creado exitosamente',
                'permission' => $permission
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $permissionId)  // Se obtiene el id del permiso a actualizar
    {
        try {
            $permission = Permission::find($permissionId);

            if (!$permission) {
                return response()->json([
                    'message' => 'Permiso no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:security.permissions,' . $permission->id,     // Ignora el id del permiso al verificar unicidad.
                'category' => 'required|string',
                'module' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error en la validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $permission->update($request->all());
            return response()->json([
                'message' => 'Permiso actualizado exitosamente',
                'permission' => $permission
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($permissionId)
    {
        try {
            $permission = Permission::find($permissionId);

            if (!$permission) {
                return response()->json([
                    'message' => 'Permiso no encontrado'
                ], 404);
            } else {
                $permission->delete();
                return response()->json([
                    'message' => 'Permiso eliminado exitosamente'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
