<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;     // Para que el usuario autenticado pueda tener muchas relaciones con la tabla aplicativos

// Un modelo es como un objeto que representa una tabla de la base de datos. 
// En este caso, el modelo User representa la tabla users de la base de datos.
// Tiene las mismas columnas que la tabla users de la base de datos.

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'security.users';
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [         // Las columnas que se pueden llenar masivamente
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'status',
        'failed_login_attempts',
        'locked_until',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [           // Las columnas que no se pueden mostrar al usuario
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [                            // Casteo de datos de las columnas
        'status' => 'boolean',
        'locked_until' => 'datetime',
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
            'username' => $this->username,
            'email' => $this->email,
        ];
    }

    // Constructor de nombre completo

    protected function FullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Verifica si el usuario esta bloqueado
    protected function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    // Bloqueo de usuario por intentos fallidos
    protected function lock()
    {
        $this->failed_login_attempts++;
        if ($this->failed_login_attempts >= 3) {
            $this->update(['locked_until' => now()->addMinutes(5)]);
            $this->update(['status' => false]);
        }
    }

    // Desbloqueo de usuario
    protected function unlock()
    {
        $this->update(['failed_login_attempts' => 0]);
        $this->update(['locked_until' => null]);
        $this->update(['status' => true]);
    }

    // Relación con la tabla aplicativos
    public function aplicativos() : HasMany // Indica que la función devuelve una relación HasMany
    {
        return $this->hasMany(Aplicativo::class); // El usuario autenticado puede tener muchas relaciones con la tabla aplicativos
    }
}
