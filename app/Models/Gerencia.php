<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gerencia extends Model
{
    use HasFactory;

    protected $table = 'gerencias';  // Especifica el nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre_gerencia',
        'abreviacion',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function aplicativos()
    {
        return $this->hasMany(Aplicativo::class);
    }
}
