<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicativo extends Model
{
    use HasFactory;
    
    protected $table = 'aplicativos';  // Especifica el nombre y esquema de la tabla en la base de datos

    protected $fillable = [
        'aplicativo',
        'alias',
        'responsable',
        'pap',
        'estatus',
        'descripcion',
    ];

    public function detallesAplicativos()
    {
        return $this->hasOne(DetalleAplicativo::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable'); // Un aplicativo tiene un responsable
    }
}
