<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:ver.usuarios')->only(['index']);
        $this->middleware('permission:gestionar.usuarios')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['gerencia', 'roles'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'username' => $validatedData['username'],
            'first_name'  => $validatedData['first_name'],
            'last_name'       => $validatedData['last_name'],
            'email'          => $validatedData['email'],
            'password'       => bcrypt($validatedData['password']),
            'is_active'         => $validatedData['is_active'] ?? true,
            'gerencia_id'    => $validatedData['gerencia_id'],
        ]);

        $roles = $validatedData['roles'] ?? ['colaborador'];
        $user->syncRoles($roles);

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario registrado exitosamente',
            'data' => $user->load(['gerencia', 'roles'])
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $userID)
    {
        $user = User::find($userID);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $validatedData = $request->validated();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario actualizado exitosamente',
            'data' => $user->load(['gerencia', 'roles'])
        ]);
    }

    /**
     * Remove the specified resource in storage.
     */

    public function destroy($userID)
    {
        $user = User::find($userID);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }
}