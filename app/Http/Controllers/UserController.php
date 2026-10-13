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

        $user->syncRoles($request->roles);

        return $this->successResponse('Usuario creado exitosamente', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $userId)
    {
        $this->authorize('update', $userId);

        $user = User::findOrFail($userId);
        
        $user->update($request->validated());
        $user->syncRoles($request->roles);

        return $this->successResponse('Usuario actualizado exitosamente', $user);
    }

    /**
     * Remove the specified resource in storage.
     */

    public function destroy($userId)
    {
        $this->authorize('delete', $userId);

        $user = User::findOrFail($userId);

        $user->delete();

        return $this->successResponse('Usuario eliminado exitosamente');
    }
}
