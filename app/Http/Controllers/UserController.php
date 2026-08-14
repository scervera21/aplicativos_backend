<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $users = User::all(); 

            return response()->json([
                "message" => 'Datos obtenidos exitosamente',
                "data" => $users
            ], 200);
            
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:20', Rule::unique('users', 'username')],
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email')],
            'password' => 'required|string|min:6',
        ]);

        $user = User::create($validated);

        return response()->json([
            "message" => 'Datos guardados exitosamente',
            "data" => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        return response()->json([
            "message" => 'Datos obtenidos exitosamente',
            "data" => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->update($request->all());

        return response()->json([
            "message" => 'Datos actualizados exitosamente',
            "data" => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();

        return response()->json([
            "message" => 'Datos eliminados exitosamente',
            "data" => $user
        ], 200);
    }
}
