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

        $users = User::all();

        return response()->json([
            "message" => 'Datos obtenidos exitosamente',
            "data" => $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());

        if($request->has('role')) {
            $role = Role::where('name', $request->role);
            if($role) {
                $user->assignRole($role);
            }
        } else {
            $user->assignRole('usuario');
        }
        
        return response()->json([
            "success" => true,
            "message" => 'Usuario guardado exitosamente',
            "data" => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */

    public function show(User $user)
    {
        $this->authorize('view', $user);

        User::findOrFail($user);

        if(!$user) {
            return response()->json([
                "message" => 'Usuario no encontrado',
            ], 404);
        }

        return response()->json([
            "message" => 'Usuario obtenido exitosamente',
            "data" => $user
        ]);
    }

    public function search($user) {

        $username = User::where('username', $user)->get();

        if (!$username) {
            return response()->json([
                "message" => 'Usuario no encontrado',
            ], 404);
        }
        return response()->json([
            "message" => 'Usuario obtenido exitosamente',
            "data" => $username
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        User::findOrFail($user);

        if(!$user) {
            return response()->json([
                "message" => 'Usuario no encontrado',
            ], 404);
        }

        $user->update($request->validated());
        
        if($request->has('role')) {
            $role = Role::where('name', $request->role);
            if($role) {
                $user->syncRoles($role);
            }
        }
        
        return response()->json([
            "success" => true,
            "message" => 'Usuario actualizado exitosamente',
            "data" => $user,
            "rol" => $user->role->pluck('name'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        User::findOrFail($user);

        if(!$user) {
            return response()->json([
                "message" => 'Usuario no encontrado',
            ], 404);
        }

        $user->delete();

        return response()->json([
            "success" => true,
            "message" => 'Usuario eliminado exitosamente',
        ]);
    }
}
