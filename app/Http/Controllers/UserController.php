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
            "message" => 'Usuario guardado exitosamente',
            "data" => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorize('view', User::class);

        $user = User::findOrFail($id);

        if(!$user) {
            return response()->json([
                "message" => 'Usuario no encontrado',
            ], 404);
        }

        return response()->json([
            "message" => 'Usuario obtenido exitosamente',
            "data" => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {
        $this->authorize('update', User::class);

        $user = User::findOrFail($id);

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
            "message" => 'Usuario actualizado exitosamente',
            "data" => $user,
            "rol" => $user->role->pluck('name'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);

        $user = User::findOrFail($user);

        if(!$user) {
            return response()->json([
                "message" => 'Usuario no encontrado',
            ], 404);
        }

        $user->delete();

        return response()->json([
            "message" => 'Usuario eliminado exitosamente',
        ], 200);
    }
}
