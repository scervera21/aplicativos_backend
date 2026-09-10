<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Definimos el 'guard' por defecto y el proveedor de contraseñas por defecto
    | para la aplicación. Los principales campos son:
    |   - guard: el 'guard' por defecto que se utilizará para autenticar a los usuarios
    |   - passwords: el proveedor de contraseñas por defecto que se utilizará para autenticar a los usuarios
    |
    */

    'defaults' => [
        'guard' => 'api',                // Define el guard por defecto (API)
        'passwords' => 'users',          // Define el proveedor de contraseñas por defecto (users)
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | 
    | Definimos los 'guards' (protectores) que se utilizarán para autenticar a los usuarios.
    | En este caso, tenemos dos guards:
    |   - web: para la autenticación por sesión
    |   - api: para la autenticación por token JWT
    | 
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'jwt',  // Utiliza el driver JWT para la autenticación de API
            'provider' => 'users', // Utiliza el proveedor de usuarios "users"
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | 
    | Definimos los proveedores de usuarios que se utilizarán para autenticar a los usuarios.
    | En este caso, tenemos un proveedor "users" que utiliza el modelo "User".
    | 
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Aquí definimos la configuración para el restablecimiento de contraseñas.
    | En este caso, tenemos una configuración "users" que utiliza el proveedor "users".
    | Los principales campos son:
    |   - provider: el proveedor de usuarios que se utilizará para autenticar a los usuarios
    |   - table: la tabla de la base de datos que se utilizará para restablecer las contraseñas
    |   - expire: el tiempo de expiración del token de restablecimiento de contraseña
    |   - throttle: el tiempo de espera entre intentos de restablecimiento de contraseña
    | 
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | El tiempo de expiración del token de restablecimiento de contraseña
    | es el tiempo que el token de restablecimiento de contraseña será válido.
    | Si el token expira, el usuario no podrá restablecer su contraseña.
    | El tiempo de espera entre intentos de restablecimiento de contraseña
    | es el tiempo que el usuario debe esperar antes de intentar restablecer
    | su contraseña nuevamente.
    |
    */

    'password_timeout' => 10800,    // 3 horas en segundos

];
