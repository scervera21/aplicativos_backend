<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],    // Rutas que permitirán el acceso CORS

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],    // Métodos HTTP permitidos

    // 'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:5173')),    // Orígenes permitidos

    'allowed_origins_patterns' => [],    // Patrones de orígenes permitidos

    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept'],    // Cabeceras permitidas

    'exposed_headers' => [],    // Cabeceras expuestas

    'max_age' => 84600,    // Tiempo máximo en segundos que se permite el cacheo de la solicitud

    'supports_credentials' => false,    // Permite el uso de credenciales (cookies, autenticación HTTP)

];
