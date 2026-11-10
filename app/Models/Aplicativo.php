<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicativo extends Model
{
    use HasFactory;
    
    protected $table = 'public.aplicativos';

    protected $fillable = [
        'nombre_aplicativo',
        'alias',
        'responsable',
        'pap',
        'estatus',
        'descripcion',
        'lenguajes_frontend',
        'lenguajes_backend',
        'bases_datos',
        'fecha_inicio',
        'fecha_fin',
        'observaciones',
        'gerencia_id'
    ];

    protected $appends = [
        'avance_total',
        'avance_pendiente',
        'metricas_actividades',
    ];

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_id');   // Un aplicativo pertenece a una gerencia
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);            // Un aplicativo tiene muchas actividades
    }

    /**
     * Accessor para el porcentaje de avance total del aplicativo (0 - 100).
     */
    public function getAvanceTotalAttribute(): float
    {
        $totalActividades = $this->actividades()->count();
        if ($totalActividades === 0) {
            return 0.0;
        }

        $sumaPorcentaje = (float) $this->actividades()->sum('porcentaje');
        return round($sumaPorcentaje / $totalActividades, 2);
    }

    /**
     * Alias para compatibilidad con llamadas previas a avanceAplicativo.
     */
    public function getAvanceAplicativoAttribute(): float
    {
        return $this->getAvanceTotalAttribute();
    }

    /**
     * Accessor para el porcentaje de avance pendiente del aplicativo (0 - 100).
     */
    public function getAvancePendienteAttribute(): float
    {
        return round(100.0 - $this->avance_total, 2);
    }

    /**
     * Accessor para métricas agregadas de actividades.
     */
    public function getMetricasActividadesAttribute(): array
    {
        return [
            'total' => $this->actividades()->count(),
            'culminadas' => $this->actividades()->where('completado', true)->count(),
            'pendientes' => $this->actividades()->where('estado', 'PENDIENTE')->count(),
            'atrasadas' => $this->actividades()->where('estado', 'ATRASADO')->count(),
        ];
    }

    // Métodos para mantener compatibilidad hacia atrás
    public function AvanceTotalAplicativo(): float
    {
        return $this->avance_total;
    }

    public function PorcentajePendiente(): float
    {
        return $this->avance_pendiente;
    }

    public function TotalActividadesPendientes(): int
    {
        return $this->actividades()
            ->where(function ($query) {
                $query->where('estado', 'PENDIENTE')
                      ->orWhere('estado', 'Sin Estatus');
            })
            ->count();
    }

    public function TotalActividadesCulminadas(): int
    {
        return $this->actividades()->where('completado', true)->count();
    }

    public function TotalActividadesAtrasadas(): int
    {
        return $this->actividades()->where('estado', 'ATRASADO')->count();
    }
}
