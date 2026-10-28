<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

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
    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'is_active',
        'failed_login_attempts',
        'locked_until',
        'created_at',
        'updated_at',
        'gerencia_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [          
        'password',
        'failed_login_attempts',
        'locked_until',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
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

    protected function incrementFailedLoginAttemps()
    {
       $this->increment('failed_login_attempts');

       // Lock account after N failed attempts
        if ($this->failed_login_attempts >= env('APP_LOGIN_ATTEMPTS')) {
            $this->update(['locked_until' => now()->addMinutes(env('APP_LOGIN_ATTEMPTS_LOCKOUT_MINUTES'))]);
            $this->update(['is_active' => false]);
        }
    }

    protected function resetFailedLoginAttemps()
    {
        $this->update(['failed_login_attempts' => 0]);
        $this->update(['locked_until' => null]);
    }


    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class);
    }

}
