<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json([
            'success' => true,
            'message' => 'Permisos obtenidos exitosamente',
            'data' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);

        $request->validate([
            'name' => 'required|string|max:10',
            'category' => 'required|string|max:10',
            'module' => 'required|string|max:10'
        ]);

        $permission = Permission::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Permiso creado exitosamente',
            'data' => $permission,
        ], 201);
    }

    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);

        return response()->json([
            'message' => 'Permiso obtenido exitosamente',
            'data' => $permission,
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);

        $permission = Permission::findOrFail($permission);

        if(!$permission) {
            return response()->json([
                'message' => 'Permiso no encontrado',
            ], 404);
        }

        $permission->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Permiso actualizado exitosamente',
            'data' => $permission,
        ]);
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);

        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permiso eliminado exitosamente',
        ]);
    }
}
