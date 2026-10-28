<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AplicativoController;
use App\Http\Controllers\GerenciaController;
use App\Http\Controllers\ActividadController;


/*
|--------------------------------------------------------------------------
| API Routes (Rutas de la API)
|--------------------------------------------------------------------------
|
| Todas las rutas están versionadas bajo el prefijo 'v1' para mantener
| una arquitectura limpia, escalable y totalmente compatible con el cliente
| frontend desarrollado en Vue 3 (Axios Base URL: http://127.0.0.1:8000/api/v1).
|
*/

Route::prefix('v1')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Rutas Públicas de Autenticación
    |--------------------------------------------------------------------------
    | Endpoints accesibles sin token previo (utilizados en la vista de Login).
    */
    Route::prefix('auth')->group(function () {
        // Inicio de sesión: recibe username y password, devuelve JWT + datos del usuario
        Route::post('/login', [AuthController::class, 'login']);
    });

    /*
    |--------------------------------------------------------------------------
    | Rutas Protegidas por Middleware JWT (auth:api)
    |--------------------------------------------------------------------------
    | Requieren que la cabecera 'Authorization: Bearer <token>' esté presente y válida.
    | Si el token falta o es inválido, Laravel responde automáticamente con HTTP 401.
    */
    Route::middleware('auth:api')->group(function () {

        /*
        |----------------------------------------------------------------------
        | Gestión de Sesión y Autenticación Activa
        |----------------------------------------------------------------------
        */
        Route::prefix('auth')->group(function () {
            // GET /api/v1/auth/me: Obtiene datos actualizados del usuario en sesión
            Route::get('/me', [AuthController::class, 'me']);

            // POST /api/v1/auth/logout: Cierre de sesión (invalida el token en el servidor)
            Route::post('/logout', [AuthController::class, 'logout']);

            // POST /api/v1/auth/refresh: Utilizado por el interceptor de Axios para refresco silencioso
            Route::post('/refresh', [AuthController::class, 'refresh']);
        });

        /*
        |----------------------------------------------------------------------
        | Gestión de Roles y Permisos (/api/v1/roles)
        |----------------------------------------------------------------------
        */
        
        // Catálogo general de permisos
        Route::get('/permisos', [RolePermissionController::class, 'listaPermisos'])
            ->middleware('permission:ver.roles');

        Route::apiResource('roles', RolePermissionController::class);

        /*
        |----------------------------------------------------------------------
        | Gestión de Usuarios (/api/v1/usuarios)
        |----------------------------------------------------------------------
        */
        
        Route::apiResource('usuarios', UserController::class);

        /*
        |----------------------------------------------------------------------
        | Gestión de Aplicativos (/api/v1/aplicativos)
        |----------------------------------------------------------------------
        */

        Route::controller(AplicativoController::class)->prefix('aplicativos')
        ->missing(function () {
            return response()->json(['message' => 'No encontrado'], 404);
        })
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/registro', 'store');
            Route::get('/{aplicativoId}/detalles', 'show'); // para ver detalles de un aplicativo
            Route::patch('/editar/{aplicativoId}', 'update');
            Route::delete('/{aplicativoId}/delete', 'destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Gestion de Gerencias (/api/v1/gerencias)
        |----------------------------------------------------------------------
        */

        Route::apiResource('gerencias', GerenciaController::class);

        /*
        |----------------------------------------------------------------------
        | Módulo: Actividades (/api/v1/actividades)
        |----------------------------------------------------------------------
        */

        Route::apiResource('actividades', ActividadController::class);
    });
});
