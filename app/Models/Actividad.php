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
        'completado',
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

}
