<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AplicativoController;

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
        Route::post('login', [AuthController::class, 'login']);
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
            Route::get('me', [AuthController::class, 'me']);

            // POST /api/v1/auth/logout: Cierre de sesión (invalida el token en el servidor)
            Route::post('logout', [AuthController::class, 'logout']);

            // POST /api/v1/auth/refresh: Utilizado por el interceptor de Axios para refresco silencioso
            Route::post('refresh', [AuthController::class, 'refresh']);
        });
        
        /*
        |----------------------------------------------------------------------
        | Módulo: Usuarios (/api/v1/users)
        |----------------------------------------------------------------------
        */

        Route::controller(AuthController::class)->prefix('users')
        ->missing(function () {
            return response()->json(['message' => 'No encontrado'], 404);
        })
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/registro', 'store');
            Route::get('/{user}', 'show');
            Route::patch('/editar/{user}', 'update');
            Route::delete('/{user}', 'destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Módulo: Aplicativos (/api/v1/aplicativos)
        |----------------------------------------------------------------------
        */

        Route::controller(AplicativoController::class)->prefix('aplicativos')
        ->missing(function () {
            return response()->json(['message' => 'No encontrado'], 404);
        })
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/registro', 'store');
            Route::get('/{id}', 'show');
            Route::patch('/actualizar/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Módulo: Roles y Permisos Spatie (/api/v1/roles)
        |----------------------------------------------------------------------
        */

        Route::controller(RoleController::class)->prefix('roles')
        ->missing(function () {
            return response()->json(['message' => 'No encontrado'], 404);
        })
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/registro', 'store');
            Route::get('/{id}', 'show');
            Route::put('/actualizar/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Módulo: Permisos Individuales (/api/v1/permissions)
        |----------------------------------------------------------------------
        */

        Route::controller(PermissionController::class)->prefix('permissions')
        ->missing(function () {
            return response()->json(['message' => 'No encontrado'], 404);
        })
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/registro', 'store');
            Route::get('/{id}', 'show');
            Route::put('/actualizar/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

    });

});
