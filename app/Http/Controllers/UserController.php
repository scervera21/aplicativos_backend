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

        try {
            $users = User::with('roles', 'gerencia')->get();

            return response()->json([
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $user = User::create($request->validated());

            if ($request->roles) {
                $user->syncRoles($request->roles);
            }

            if ($request->password) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'user' => $user->load('roles', 'gerencia'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $userId)
    {
        try {

            $user = User::find($userId)->load('roles', 'gerencia');

            $user->update($request->validated());

            if ($request->roles) {
                $user->syncRoles($request->roles);
            }

            if ($request->password) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'user' => $user->load('roles', 'gerencia'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource in storage.
     */

    public function destroy($userId)
    {
        try {

            $user = User::find($userId);
            $user->delete();
            return response()->json([
                'message' => 'Usuario eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}