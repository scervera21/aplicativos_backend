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
            'nombre_user' => $validatedData['nombre_user'],
            'primer_nombre'  => $validatedData['primer_nombre'],
            'apellido'       => $validatedData['apellido'],
            'email'          => $validatedData['email'],
            'password'       => bcrypt($validatedData['password']),
            'activo'         => $validatedData['activo'] ?? true,
            'gerencia_id'    => $validatedData['gerencia_id'],
        ]);

        // Asignar rol a usuario
        $rol = $validatedData['rol'] ?? 'Colaborador';
        $user->assignRole($rol);

        return response()->json([
            'status' => 'success',
            'message' => 'User registrado exitosamente',
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
                'message' => 'User no encontrado'
            ], 404);
        }

        $validatedData = $request->validated();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);

        if ($request->has('rol')) {
            $user->syncRoles([$request->rol]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User$user actualizado exitosamente',
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
                'message' => 'User$user no encontrado'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }
}