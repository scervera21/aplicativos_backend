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
        $this->authorize('viewAny', User::class);

        $users = User::all()->load('roles');

        return $this->successResponse('Usuarios obtenidos exitosamente', $users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());

        $this->assignRoles($user, $request->roles);

        return $this->successResponse('Usuario creado exitosamente', $user);
    }

    public function assignRoles(User $user, array $roles)
    {

        $roleIds = [];
        foreach ($roles as $role) {
            $role = Role::where('name', $role)->first();
            if ($role) {
                $roleIds[] = $role->id;
            }
        }
        $user->syncRoles($roleIds);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $userId)
    {
        $this->authorize('update', $userId);

        $user = User::find($userId);

        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', 404);
        }

        $user->update($request->validated());

        return $this->successResponse('Usuario actualizado exitosamente', $user);
    }

    /**
     * Remove the specified resource in storage.
     */

    public function destroy(User $userId)
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
