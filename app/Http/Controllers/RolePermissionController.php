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
        $this->middleware('permission:ver.roles')->only(['index', 'show', 'listaPermisos']);
        $this->middleware('permission:gestionar.roles')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    /**
     * Listar el catálogo general de permisos (Útil para marcar casillas en la interfaz). (GET /api/roles/permisos)
     */
    public function listaPermisos() 
    {
        $permissions = Permission::orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $permissions
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

        $roleName = strtoupper($validatedData['name']);

        // Ningún rol excepto ADMINISTRADOR puede tener todos los permisos
        if ($roleName !== 'ADMINISTRADOR' && !empty($validatedData['permissions'])) {
            $totalSystemPermissions = Permission::count();
            if (count($validatedData['permissions']) >= $totalSystemPermissions) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Solo el rol ADMINISTRADOR tiene asignados todos los permisos'
                ], 403);
            }
        }

        $role = Role::create([
            'name' => $roleName,
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
        // Buscar el rol especificado y extraer solo el nombre de los permisos que tiene
        $permissionsOfRole = Role::find($roleID)->permissions->pluck('name');

        if (!$permissionsOfRole) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rol no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $permissionsOfRole
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

        // Si el rol es administrador, no se puede editar
        if ($role->name === 'administrador' || $role->name === 'ADMINISTRADOR') {
            return response()->json([
                'status' => 'error',
                'message' => 'No se puede editar el rol ADMINISTRADOR'
            ], 403);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:50|unique:roles,name,' . $roleID,
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $targetName = isset($validatedData['name']) ? strtoupper($validatedData['name']) : $role->name;  

        // Ningún rol excepto ADMINISTRADOR puede tener todos los permisos
        if ($targetName !== 'ADMINISTRADOR' && isset($validatedData['permissions'])) {
            $totalSystemPermissions = Permission::count();
            if (count($validatedData['permissions']) >= $totalSystemPermissions) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Únicamente el rol ADMINISTRADOR puede tener asignados todos los permisos del sistema'
                ], 422);
            }
        }

        // Actualizar nombre si viene en la petición
        if (isset($validatedData['name'])) {
            $role->update(['name' => $targetName]);
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

        // Para validar que no se puede eliminar el rol con permisos de administrador
        if ($role->permissions()->get()->pluck('name')->toArray() == Permission::all()->pluck('name')->toArray()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se puede eliminar el rol con permisos de administrador'
            ], 403);
        }

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Rol eliminado exitosamente'
        ]);
    }
}
