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
        | Módulo: Roles y Permisos (/api/v1/roles)
        |----------------------------------------------------------------------
        */
        
        Route::get('/roles', [RolePermissionController::class, 'index'])
            ->middleware('permission:ver.roles');
        
        Route::post('/roles/create-rol', [RolePermissionController::class, 'store'])
            ->middleware('permission:gestionar.roles');
        
        Route::get('/roles/{roleID}/permisos', [RolePermissionController::class, 'show'])
            ->middleware('permission:ver.roles');
        
        Route::match(['put', 'patch'], '/roles/{roleID}/update', [RolePermissionController::class, 'update'])
            ->middleware('permission:gestionar.roles');
        
        Route::delete('/roles/{roleID}', [RolePermissionController::class, 'destroy'])
            ->middleware('permission:gestionar.roles');

        /*
        |----------------------------------------------------------------------
        | Módulo: Usuarios (/api/v1/usuarios)
        |----------------------------------------------------------------------
        */

        Route::get('/usuarios', [UserController::class, 'index'])
            ->middleware('permission:ver.usuarios');
        
        Route::post('/usuarios/create-user', [UserController::class, 'store'])
            ->middleware('permission:gestionar.usuarios');
        
        Route::match(['put', 'patch'], '/usuarios/{userID}/update', [UserController::class, 'update'])
            ->middleware('permission:gestionar.usuarios');
        
        Route::delete('/usuarios/{userID}', [UserController::class, 'destroy'])
            ->middleware('permission:gestionar.usuarios');

        /*
        |----------------------------------------------------------------------
        | Módulo: Gerencias (/api/v1/gerencias)
        |----------------------------------------------------------------------
        */

        Route::get('/gerencias', [GerenciaController::class, 'index']);        
        Route::post('/gerencias/create-gerencia', [GerenciaController::class, 'store']);        
        Route::match(['put', 'patch'], '/gerencias/{gerenciaID}/update', [GerenciaController::class, 'update']);        
        Route::delete('/gerencias/{gerenciaID}', [GerenciaController::class, 'destroy']);

        /*
        |----------------------------------------------------------------------
        | Módulo: Actividades (/api/v1/actividades)
        |----------------------------------------------------------------------
        */

        Route::get('/actividades', [ActividadController::class, 'index'])
            ->middleware('permission:ver.actividades');
        
        Route::post('/actividades/create-actividad', [ActividadController::class, 'store'])
            ->middleware('permission:gestionar.actividades');
        
        Route::match(['put', 'patch'], '/actividades/{actividadID}/update', [ActividadController::class, 'update'])
            ->middleware('permission:gestionar.actividades');
        
        Route::delete('/actividades/{actividadID}', [ActividadController::class, 'destroy'])
            ->middleware('permission:gestionar.actividades');

        /*
        |----------------------------------------------------------------------
        | Módulo: Aplicativos (/api/v1/aplicativos)
        |----------------------------------------------------------------------
        */

        Route::get('/aplicativos', [AplicativoController::class, 'index'])
            ->middleware('permission:ver.aplicativos');
        
        Route::post('/aplicativos/create-aplicativo', [AplicativoController::class, 'store'])
            ->middleware('permission:gestionar.aplicativos');

        Route::get('/aplicativos/{aplicativoID}/detalles', [AplicativoController::class, 'show'])
            ->middleware('permission:ver.aplicativos');
        
        Route::match(['put', 'patch'], '/aplicativos/{aplicativoID}/update', [AplicativoController::class, 'update'])
            ->middleware('permission:gestionar.aplicativos');
        
        Route::delete('/aplicativos/{aplicativoID}', [AplicativoController::class, 'destroy'])
            ->middleware('permission:gestionar.aplicativos');
    });
});
