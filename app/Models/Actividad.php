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
        'unidad',
        'prioridad',
        'estado',
        'completado',
        'comentarios',
        'aplicativo_id',
    ];

    public function aplicativos()
    {
        return $this->belongsTo(Aplicativo::class); // Una actividad pertenece a un aplicativo
    }

    public function gerencias()
    {
        return $this->belongsToMany(Gerencia::class, 'colaboradores', 'actividad_id', 'gerencia_id'); // En el modelo actividades se define la relación muchos a muchos con gerencia a traves de la tabla intermedia 'colaboradores' con los campos actividad_id y gerencia_id
    }
}
