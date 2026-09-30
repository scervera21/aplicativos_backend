<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    
    protected $table = 'actividades';  // Especifica el nombre de la tabla en la base de datos

    protected $fillable = [
        'actividad',
        'creado_el',
        'culminado_el',
        'area',
        'prioridad',
        'colaboradores',
        'porcentaje_avance',
        'comentarios',
        'aplicativo_id',
    ];

    protected $casts = [
        'colaboradores' => 'array',
    ];

    public function aplicativos()
    {
        return $this->belongsTo(Aplicativo::class); // Una actividad pertenece a un aplicativo
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class); // Una actividad pertenece a un usuario
    }

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class); // Una actividad pertenece a una gerencia
    }

}
