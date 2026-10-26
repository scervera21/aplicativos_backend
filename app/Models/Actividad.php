<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    
    protected $table = 'public.actividades';

    protected $fillable = [
        'nombre_actividad',
        'fecha_creacion',
        'fecha_culminacion',
        'area_id',
        'prioridad',
        'estado',
        'porcentaje',
        'completado',
        'comentarios',
        'aplicativo_id',
    ];

    protected $casts = [
        'completado' => 'boolean',
    ];

    public function aplicativo()
    {
        return $this->belongsTo(Aplicativo::class); // Una actividad pertenece a un aplicativo
    }

    public function area()
    {
        return $this->belongsTo(Area::class); // Una actividad pertenece a un area
    }

    public function gerenciasColaboradoras()
    {
        return $this->belongsToMany(Gerencia::class, 'colaboradores', 'actividad_id', 'gerencia_id'); // Una actividad puede tener muchas gerencias colaboradoras
    }

    // Si llega a 100, completado pasa a true de forma automática
    // Actualiza el estado a Culminado cuando el porcentaje llega a 100
    // Actualiza la fecha de culminación cuando el porcentaje llega a 100

    public function setPorcentajeAttribute($value) {
        $this->attributes['porcentaje'] = $value;
        if ($value == 100) {
            $this->attributes['completado'] = true;
            $this->attributes['estado'] = 'Culminado';
            $this->attributes['fecha_culminacion'] = now()->format('Y-m-d');
        } else {
            $this->attributes['completado'] = false;
            $this->attributes['fecha_culminacion'] = null;
        }
    }
}
