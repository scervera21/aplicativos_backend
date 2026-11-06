<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted; //para mostrar las consultas en consola

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
            $passwordRule = Password::min(6) // longitud minima 6 caracteres
                ->letters()  // debe tener al menos una letra
                ->numbers(); // debe tener al menos un numero

            // Solo consultar el servicio externo de brechas en producción para evitar errores de red en desarrollo/intranet
            return app()->isProduction() ? $passwordRule->uncompromised() : $passwordRule;
        });

        //Para mostrar las consultas en consola
        /*

        DB::listen(function (QueryExecuted $query) {
            'query' => $query->sql, //query sql
            'bindings' => $query->bindings, //parametros de la consulta
            'time' => $query->time, //tiempo de ejecucion
        });

        */
    }
}
