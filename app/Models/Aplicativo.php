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

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_id');   // Un aplicativo pertenece a una gerencia
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);            // Un aplicativo tiene muchas actividades
    }

    // MÉTODO PARA CALCULAR EL PORCENTAJE GENERAL DEL APLICATIVO

    public function AvanceTotalAplicativo()
    {
        $totalActividades = $this->actividades()->count();
        if ($totalActividades === 0) return 0;

        $sumaPorcentaje = $this->actividades()->sum('porcentaje');
        $avanceAplicativo = round(($sumaPorcentaje / $totalActividades), 2);
        return (int) $avanceAplicativo; //Retorna el porcentaje general del aplicativo en entero
    }

    // MÉTODO PARA CALCULAR EL PORCENTAJE PENDIENTE DEL APLICATIVO

    public function PorcentajePendiente()
    {
        return (int) (1 - $this->AvanceTotalAplicativo()); //Retorna el porcentaje pendiente del aplicativo en entero
    }

    // TOTALES DE ACTIVIDADES POR ESTADO

    public function TotalActividadesPendientes()
    {
        $pendientes = $this->actividades()->where('estado', 'Sin Estatus')->orWhere('estado', 'Pendiente')->count();
        return $pendientes;
    }

    public function TotalActividadesCulminadas()
    {
        $culminadas = $this->actividades()->where('completado', true)->count(); //Retorna el total de actividades culminadas
        return $culminadas;
    }

    public function TotalActividadesAtrasadas()
    {
        $atrasadas = $this->actividades()->where('estado', 'Atrasado')->count();
        return $atrasadas;
    }
}
