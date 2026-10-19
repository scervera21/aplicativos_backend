<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();

        return $this->successResponse('Usuarios obtenidos exitosamente', [
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {

        $user = User::create($request->validated());

        $user->syncRoles($request->roles);

        return $this->successResponse('Usuario creado exitosamente', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $userId)
    {

        $roles = Role::all();
        $user = User::find($userId)->load('roles');

        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', 404);
        }

        $user->update($request->validated());

        $user->syncRoles($request->roles);

        return $this->successResponse('Usuario actualizado exitosamente', [
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource in storage.
     */

    public function destroy($userId)
    {

        $user = User::find($userId);

        /* Si el usuario autenticado tiene rol de administrador no se puede eliminar hasta que 
            asigne un nuevo usuario administrador
        */

        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', 404);
        } else {
            $user->delete();
            return $this->successResponse('Usuario eliminado exitosamente');
        }
    }
}
