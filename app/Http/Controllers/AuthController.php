<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    /**
     * Iniciar sesión y obtener token JWT.
     */
    
    public function login(Request $request): JsonResponse
    {

        // Valida las credenciales
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Intenta autenticar usando el guard API configurado en auth.php
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $user = auth('api')->user();
        
        // Cierra sesión si está inactivo
        if (!$user->is_active) {
            auth('api')->logout();
            return response()->json([
                'status' => 'error',
                'message' => 'El usuario se encuentra inactivo o inhabilitado'
            ], 403);
        }
        
        // Retorna el token y la información del usuario.
        return $this->respondWithToken($token);
    }

    /**
     * Retorna la información del usuario autenticado actual.
     */
    public function me(): JsonResponse
    {
        $user = auth('api')->user();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name')
            ],
        ]);
    }

    /**
     * Cierra la sesión invalidando el token JWT actual en el servidor.
     */
    public function logout(): JsonResponse
    {

        try {
            auth('api')->logout();

            return response()->json([
                'status' => 'success',
                'message' => 'Sesión cerrada exitosamente'
            ]);
        } catch (\Exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo cerrar la sesión, intente de nuevo'
            ], 500);
        }
    }

    /**
     * Obtiene un nuevo token JWT renovado.
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = auth('api')->refresh();
            return $this->respondWithToken($newToken);
        } catch (\Exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo renovar el token, intente de nuevo'
            ], 401);
        }
    }

    /**
     * Retorna una respuesta JSON con el token y la información del usuario.
     *
     * @param string $token El token JWT.
     * @return JsonResponse La respuesta JSON.
     */
    protected function respondWithToken($token): JsonResponse
    {
        $user = auth('api')->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $user ? [
                    'id' => $user->id,
                    'username' => $user->username,
                    'roles' => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name')
                ] : null,
            ],
        ]);
    }
}
