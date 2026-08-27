<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de CORS (Cross-Origin Resource Sharing)
    |--------------------------------------------------------------------------
    |
    | Define las reglas para permitir que el cliente frontend (desarrollado en Vue 3)
    | pueda realizar peticiones HTTP seguras hacia esta API de Laravel desde
    | un origen o puerto diferente (por ejemplo: http://localhost:5173).
    |
    */

    // Rutas de Laravel que permitirán el acceso desde aplicaciones externas
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Métodos HTTP permitidos para interactuar con los recursos de la API
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    /*
    | MOTIVO DEL CAMBIO / AJUSTE:
    | Se habilitó 'allowed_origins' => ['*'] para permitir que el servidor de
    | desarrollo de Vite (usualmente en http://localhost:5173 o http://127.0.0.1:5173)
    | pueda enviar peticiones HTTP (Login, Refresh, CRUD) sin ser bloqueado por la
    | política de seguridad Same-Origin del navegador.
    */
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    // Cabeceras HTTP permitidas en las solicitudes (necesario para Authorization con JWT)
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept'],

    'exposed_headers' => [],

    // Tiempo máximo en segundos que el navegador puede cachear la respuesta preflight OPTIONS
    'max_age' => 84600,

    'supports_credentials' => false,

];
