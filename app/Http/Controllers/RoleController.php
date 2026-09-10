<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json([
            'success' => true,
            'message' => 'Roles obtenidos exitosamente',
            'data' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $request->validate([
            'name' => 'required|string|max:10',
        ]);

        $role = Role::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Rol creado exitosamente',
            'data' => $role,
        ], 201);
    }

    public function show(Role $role)
    {
        $this->authorize('view', $role);

        return response()->json([
            'message' => 'Rol obtenido exitosamente',
            'data' => $role,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $role = Role::findOrFail($role);

        if(!$role) {
            return response()->json([
                'message' => 'Rol no encontrado',
            ], 404);
        }

        $role->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado exitosamente',
            'data' => $role,
        ]);
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        if($role->name === 'administrador') {
            return response()->json([
                'message' => 'No se puede eliminar el rol de administrador',
            ], 403);
        } 

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rol eliminado exitosamente',
        ]);
    }
}
