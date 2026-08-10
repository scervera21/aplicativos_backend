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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Acceso denegado'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Se ha cerrado la sesión correctamente']);
    }

    public function refresh()   
    {
        return $this->respondWithToken(auth()->refresh());  // Actualiza el token de autenticación
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,   // Token de autenticación que se usa para autenticarse en la API
            'token_type' => 'bearer',   // Tipo de token de autenticación que se usa para autenticarse en la API
            'expires_in' => auth()->factory()->getTTL() * 60  // Tiempo de expiración del token en segundos
        ]);
    }
}