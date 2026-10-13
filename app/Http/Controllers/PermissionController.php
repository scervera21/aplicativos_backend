<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    public function index()
    {
        $permissions = Permission::all();
        return $this->successResponse('Permisos obtenidos exitosamente', $permissions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name',
            'category' => 'required|string',
            'module' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error en la validación', 422, $validator->errors());
        }

        $permission = Permission::create($request->all());

        return $this->successResponse('Permiso creado exitosamente', $permission);
    }

    public function update(Request $request, Permission $permission)  // Se obtiene el id del permiso a actualizar
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $permission->id,     // Ignora el id del permiso al verificar unicidad.
            'category' => 'required|string',
            'module' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error en la validación', 422, $validator->errors());
        }

        $permission->update($request->all());

        return $this->successResponse('Permiso actualizado exitosamente', $permission);
    }

    public function destroy($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);

        $permission->delete();

        return $this->successResponse('Permiso eliminado exitosamente');
    }
}
