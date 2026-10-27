<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;


class RolePermissionController extends Controller
{
    /**
     * Constructor para proteger los endpoints con JWT y restricciones de permisos.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:ver.roles')->only(['index', 'show']);
        $this->middleware('permission:gestionar.roles')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $roles = Role::with('permissions')->get();

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    /**
     * Listar el catálogo general de permisos (Útil para marcar casillas en la interfaz). (GET /api/permisos)
     */
    public function listaPermisos()
    {
        $permisos = Permission::all();

        return response()->json([
            'status' => 'success',
            'data' => $permisos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name|max:50',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::create([
            'name' => $validatedData['name'],
            'guard_name' => 'api'
        ]);

        // Asignar permisos si se enviaron en la petición
        if (!empty($validatedData['permissions'])) {
            $role->givePermissionTo($validatedData['permissions']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Rol creado exitosamente',
            'data' => $role->load('permissions')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($roleID)
    {
        $role = Role::with('permissions')->find($roleID);

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rol no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $roleID)
    {
        $role = Role::find($roleID);

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rol no encontrado'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:50|unique:roles,name,' . $roleID,
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        // Actualizar nombre si viene en la petición
        if (isset($validatedData['name'])) {
            $role->update(['name' => $validatedData['name']]);
        }

        // Sincronizar permisos si se enviaron
        if (isset($validatedData['permissions'])) {
            $role->syncPermissions($validatedData['permissions']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Rol actualizado exitosamente',
            'data' => $role->load('permissions')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($roleID)
    {
        $role = Role::find($roleID);

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rol no encontrado'
            ], 404);
        }

        if ($role->name === 'Administrador') {
            return response()->json([
                'status' => 'error',
                'message' => 'No se puede eliminar el rol protegido del sistema.'
            ], 403);
        }

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Rol eliminado exitosamente'
        ]);
    }
}
