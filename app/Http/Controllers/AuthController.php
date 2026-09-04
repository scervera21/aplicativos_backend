<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Models\Role;

class AuthController extends Controller
{
    /**
     * Inicia sesión autenticando al usuario y retornando un token JWT junto con su información y permisos.
     * 
     * MOTIVO DEL CAMBIO / AJUSTE:
     * - Se utiliza 'with('roles.permissions')' (Eager Loading) para traer en una sola consulta
     *   a la base de datos el usuario, sus roles asociados y los permisos de cada rol (Spatie ACL).
     * - Se agregó la validación de 'status' para bloquear el acceso a usuarios inactivos o deshabilitados.
     * - Se incluye la clave 'user' => $user en la respuesta JSON para que el frontend (Pinia Store)
     *   pueda almacenar inmediatamente los datos del usuario autenticado sin requerir una segunda
     *   petición HTTP, optimizando el rendimiento y permitiendo calcular permisos de inmediato.
     */
    public function login(Request $request)
    {  
        // Búsqueda del usuario cargando sus roles y permisos para el control de acceso en el frontend
        $user = User::with('roles.permissions')->where('username', $request->username)->first();

        // Validación: Usuario inexistente
        if (!$user) {
            return response()->json([
                'message' => 'Usuario incorrecto',
            ], 401);
        }

        // Validación: Contraseña incorrecta
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Contraseña incorrecta',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validación de Estado Activo (status)
        |--------------------------------------------------------------------------
        | Si el status del usuario es false (0 en la base de datos), significa que
        | su cuenta está inactiva o deshabilitada, por lo que se rechaza el login
        | con un código HTTP 403 Forbidden antes de generar el token JWT.
        */
        if (!$user->status) {
            return response()->json([
                'message' => 'El usuario se encuentra inactivo. Comuníquese con el administrador.',
            ], 403);
        }

        // Generación del token JWT para la sesión del usuario autenticado
        $token = auth('api')->login($user);

        // Respuesta completa esperada por el frontend (auth.ts -> loginSuccess)
        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // Tiempo de expiración en segundos
            'user' => $user, // Objeto de usuario completo con roles y permisos incluidos
        ]);
    }

    /**
     * Registra un nuevo usuario.
     * 
     * @param \App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\JsonResponse
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
                $user->assignRole('Usuario');
            }
            
            return response()->json([
                "message" => 'Usuario guardado exitosamente',
                "data" => $user
            ], 201);

    }

    /**
     * Obtiene todos los usuarios registrados.
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
     * Muestra el usuario registrado
     */

    public function show(User $user)
    {
        User::findOrFail($user);

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
     * Actualiza el usuario con el id proporcionado.
     */
    public function update(UserRequest $request, User $user)
    {

        $this->authorize('update', $user);

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
     * Elimina el usuario con el id proporcionado.
     */
    public function destroy(User $user)
    {

        $this->authorize('delete', $user);
        $user->delete();

        return response()->json([
            "message" => 'Usuario eliminado exitosamente',
        ], 200);
    }

    /**
     * Retorna la información del usuario autenticado actual.
     * 
     * MOTIVO DEL CAMBIO / AJUSTE:
     * - Se agregó la carga de relaciones 'roles.permissions' para que el frontend pueda sincronizar
     *   o verificar la identidad y permisos actualizados del usuario en cualquier momento.
     */
    public function me()
    {
        return response()->json(auth('api')->user()?->load('roles.permissions'));
    }

    /**
     * Cierra la sesión invalidando el token JWT actual en el servidor.
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Se ha cerrado la sesión correctamente']);
    }

    /**
     * Renueva el token JWT vencido o próximo a vencer (Silent Token Refresh).
     * 
     * MOTIVO DEL CAMBIO / AJUSTE:
     * - Verifica si el usuario sigue con estatus activo ('status === true').
     * - Retorna el nuevo 'token', su tiempo de vida 'expires_in' y el 'user' actualizado.
     * - Permite que el interceptor de Axios en el frontend ('src/services/api.ts')
     *   renueve la sesión de forma transparente cuando se detecta un error 401.
     */
    public function refresh()   
    {
        $user = auth('api')->user();

        // Si el usuario fue desactivado mientras tenía una sesión abierta, rechazamos el refresco
        if ($user && !$user->status) {
            auth('api')->logout();
            return response()->json([
                'message' => 'La sesión ha sido finalizada.',
            ], 401);
        }

        return response()->json([
            'token' => auth('api')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user?->load('roles.permissions'),
        ]);
    }
}
