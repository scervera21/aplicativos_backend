<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:20|unique:users',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function login(Request $request)
    {  

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario incorrecto',
            ], 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Contraseña incorrecta',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    // public function me()
    // {
    //     return response()->json(auth()->user());
    // }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Se ha cerrado la sesión correctamente']);
    }

    // public function refresh()   
    // {
    //     return auth()->refresh();
    // }
}