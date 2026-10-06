<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicativo extends Model
{
    use HasFactory;
    protected $table = 'aplicativos';  // Especifica el nombre y esquema de la tabla en la base de datos

    protected $fillable = [
        'nombre_aplicativo',
        'abreviacion',
        'descripcion',
        'responsable',
        'pap',
        'estatus',
        'gerencia_id',
    ];

    public function detallesAplicativos()
    {
        return $this->hasOne(DetalleAplicativo::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_linea');
    }
}
