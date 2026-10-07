<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gerencia extends Model
{
    use HasFactory;

    protected $table = 'gerencias';  // Especifica el nombre de la tabla en la base de datos

    protected $fillable = [
        'gerencia',
        'sigla',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);     // Una gerencia tiene muchos usuarios
    }

    public function aplicativos()
    {
        return $this->hasMany(Aplicativo::class); // Una gerencia tiene muchos aplicativos
    }

    public function actividades()
    {
        return $this->belongsToMany(Actividad::class); // Una gerencia puede tener muchas actividades
    }
}
