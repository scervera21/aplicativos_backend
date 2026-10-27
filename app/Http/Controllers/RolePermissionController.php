<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;


class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissions = Permission::all();
            $roles = Role::all();
            return response()->json([
                'permissions' => $permissions,
                'roles' => $roles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los permisos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'role_id' => 'required',
                'permission_id' => 'required'
            ]);
            $rolePermission = RolePermission::create([
                'role_id' => $request->role_id,
                'permission_id' => $request->permission_id
            ]);
            return response()->json([
                'message' => 'Permiso asignado exitosamente',
                'role_permission' => $rolePermission
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al asignar el permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $rolePermission = RolePermission::find($id);
            return response()->json([
                'role_permission' => $rolePermission
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'role_id' => 'required',
                'permission_id' => 'required'
            ]);
            $rolePermission = RolePermission::find($id);
            $rolePermission->update([
                'role_id' => $request->role_id,
                'permission_id' => $request->permission_id
            ]);
            return response()->json([
                'message' => 'Permiso actualizado exitosamente',
                'role_permission' => $rolePermission
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rolePermission = RolePermission::find($id);
            $rolePermission->delete();
            return response()->json([
                'message' => 'Permiso eliminado exitosamente',
                'role_permission' => $rolePermission
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
