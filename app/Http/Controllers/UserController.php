<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')->get();
        $roles = Role::all();

        return $this->successResponse('Usuarios obtenidos exitosamente', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());

        // ASIGNAR ROLES

        $roles = Role::whereIn('name', $request->roles)->pluck('id')->toArray();

        if (!$roles) {
            return $this->errorResponse('No se pudieron obtener los roles', 404);
        }

        $user->syncRoles($roles);

        return $this->successResponse('Usuario creado exitosamente', [
            'user' => $user,
            'roles' => $roles->pluck('name')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $userId)
    {
        $this->authorize('update', $userId);

        $user = User::find($userId);

        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', 404);
        }

        $user->update($request->validated());

        // ACTUALIZAR ROLES

        $roles = Role::whereIn('name', $request->roles)->pluck('id')->toArray();

        if (!$roles) {
            return $this->errorResponse('No se pudieron obtener los roles', 404);
        }
        
        $user->syncRoles($roles);

        return $this->successResponse('Usuario actualizado exitosamente', [
            'user' => $user,
            'roles' => $roles->pluck('name')
        ]);
    }

    /**
     * Remove the specified resource in storage.
     */

    public function destroy($userId)
    {
        $this->authorize('delete', $userId);

        $user = User::find($userId);

        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', 404);
        }

        $user->delete();

        return $this->successResponse('Usuario eliminado exitosamente');
    }
}
