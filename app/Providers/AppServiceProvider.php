<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definiendo la regla por defecto para contraseñas

        Password::defaults(function () {
            $passwordRule = Password::min(6)
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised(); // Comprueba si la contraseña ha sido comprometida en brechas de seguridad conocidas

            return $passwordRule;
        });
    }
}
