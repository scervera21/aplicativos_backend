<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;     // Para que el usuario autenticado pueda tener muchas relaciones con la tabla aplicativos

// Un modelo es como un objeto que representa una tabla de la base de datos. 
// En este caso, el modelo User representa la tabla users de la base de datos.
// Tiene las mismas columnas que la tabla users de la base de datos.

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [         // Las columnas que se pueden llenar masivamente
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [           // Las columnas que no se pueden mostrar al usuario
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [                            // Casteo de datos de las columnas
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

/**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()      // Se usa JWT para obtener el identificador del usuario
    {   
        //retorna el id del usuario que se va a incluir en el token
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()    // Se usa JWT para obtener el token de autenticación
    {   
        //retorna un array con los datos del usuario que se van a incluir en el token
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public function aplicativos() : HasMany // Indica que la función devuelve una relación HasMany
    {
        return $this->hasMany(Aplicativo::class); // El usuario autenticado puede tener muchas relaciones con la tabla aplicativos
    }
}
