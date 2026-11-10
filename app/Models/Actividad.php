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
        'porcentaje' => 'integer',
        'area_id' => 'integer',
        'aplicativo_id' => 'integer',
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

    /**
     * Mutator para asegurar lógica del Project Manager:
     * - Rango estricto [0, 100].
     * - Si llega a 100: completado = true, estado = 'CULMINADO', fecha_culminacion hoy.
     * - Si es menor a 100: completado = false, fecha_culminacion null, y si era CULMINADO revierte a PENDIENTE.
     */
    public function setPorcentajeAttribute($value)
    {
        $val = max(0, min(100, (int) $value));
        $this->attributes['porcentaje'] = $val;

        if ($val === 100) {
            $this->attributes['completado'] = true;
            $this->attributes['estado'] = 'CULMINADO';
            $this->attributes['fecha_culminacion'] = now()->format('Y-m-d');
        } else {
            $this->attributes['completado'] = false;
            $this->attributes['fecha_culminacion'] = null;

            $currentEstado = strtoupper($this->attributes['estado'] ?? '');
            if ($currentEstado === 'CULMINADO') {
                $this->attributes['estado'] = 'PENDIENTE';
            }
        }
    }
}
