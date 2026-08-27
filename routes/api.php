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

        // Registro de nuevos usuarios en el sistema
        Route::post('register', [AuthController::class, 'register']);

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
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::patch('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });

        /*
        |----------------------------------------------------------------------
        | Módulo: Aplicativos (/api/v1/aplicativos)
        |----------------------------------------------------------------------
        */
        Route::prefix('aplicativos')->group(function () {
            Route::get('/', [AplicativoController::class, 'index']);
            Route::post('/', [AplicativoController::class, 'store']);
            Route::get('/{id}', [AplicativoController::class, 'show']);
            Route::patch('/{id}', [AplicativoController::class, 'update']);
            Route::delete('/{id}', [AplicativoController::class, 'destroy']);
        });

        /*
        |----------------------------------------------------------------------
        | Módulo: Roles y Permisos Spatie (/api/v1/roles)
        |----------------------------------------------------------------------
        */
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/{id}', [RoleController::class, 'show']);
            Route::put('/{id}', [RoleController::class, 'update']);
            Route::delete('/{id}', [RoleController::class, 'destroy']);
        });

        /*
        |----------------------------------------------------------------------
        | Módulo: Permisos Individuales (/api/v1/permissions)
        |----------------------------------------------------------------------
        */
        Route::prefix('permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'index']);
            Route::post('/', [PermissionController::class, 'store']);
            Route::get('/{id}', [PermissionController::class, 'show']);
            Route::put('/{id}', [PermissionController::class, 'update']);
            Route::delete('/{id}', [PermissionController::class, 'destroy']);
        });

    });

});
