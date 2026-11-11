<?php

namespace App\Services;

use App\Models\Aplicativo;
use App\Models\Actividad;
use App\Models\Area;
use Illuminate\Support\Collection;

class ProjectManagerService
{
    /**
     * Obtiene la estructura completa del Project Manager para un aplicativo específico:
     * - Datos del aplicativo y gerencia responsable
     * - Indicadores globales calculados (Avance Total, Avance Pendiente, Totales por estado)
     * - Ciclo de vida agrupado por Áreas predefinidas con sub-avances
     */
    public function getAplicativoProjectData(int $aplicativoId): array
    {
        $aplicativo = Aplicativo::with([
            'gerencia',
            'actividades.area',
            'actividades.gerenciasColaboradoras'
        ])->findOrFail($aplicativoId);

        // Áreas predefinidas del sistema en orden
        $areas = Area::orderBy('id', 'asc')->get()->map(function ($area) use ($aplicativo) {
            $actividadesArea = $aplicativo->actividades->where('area_id', $area->id)->values();
            $totalArea = $actividadesArea->count();
            $sumaPorcentajes = (float) $actividadesArea->sum('porcentaje');
            $avanceArea = $totalArea > 0 ? round($sumaPorcentajes / $totalArea, 2) : 0.0;
            $culminadasArea = $actividadesArea->where('completado', true)->count();

            return [
                'area_id' => $area->id,
                'nombre_area' => $area->nombre_area,
                'total_actividades' => $totalArea,
                'actividades_culminadas' => $culminadasArea,
                'avance_area' => $avanceArea,
                'actividades' => $actividadesArea
            ];
        });

        return [
            'aplicativo' => [
                'id' => $aplicativo->id,
                'nombre_aplicativo' => $aplicativo->nombre_aplicativo,
                'alias' => $aplicativo->alias,
                'responsable' => $aplicativo->responsable,
                'gerencia_id' => $aplicativo->gerencia_id,
                'gerencia_responsable' => $aplicativo->gerencia?->sigla ?: ($aplicativo->gerencia?->nombre_gerencia ?? 'No asignada'),
                'gerencia_sigla' => $aplicativo->gerencia?->sigla,
                'gerencia_nombre' => $aplicativo->gerencia?->nombre_gerencia,
                'estatus' => $aplicativo->estatus,
                'pap' => $aplicativo->pap,
            ],
            'indicadores' => [
                'avance_total' => $aplicativo->avance_total,
                'avance_pendiente' => $aplicativo->avance_pendiente,
                'metricas' => $aplicativo->metricas_actividades,
            ],
            'ciclo_de_vida_por_areas' => $areas,
        ];
    }

    /**
     * Automatización del checklist para una actividad:
     * - Si completado es true: asigna 100% de avance, estado 'CULMINADO' y fecha de culminación.
     * - Si completado es false: desmarca completado, restaura porcentaje a 0 (o menor a 100) y estado a 'PENDIENTE'.
     */
    public function toggleChecklist(int $actividadId, bool $completado): Actividad
    {
        $actividad = Actividad::with(['aplicativo'])->findOrFail($actividadId);

        if ($completado) {
            $actividad->porcentaje = 100;
        } else {
            // Si se desmarca, se reinicia el porcentaje a 0
            $actividad->porcentaje = 0;
        }

        $actividad->save();

        return $actividad->load(['area', 'gerenciasColaboradoras']);
    }

    /**
     * Actualiza el porcentaje dentro del Project Manager validando rangos estrictos [0, 100].
     */
    public function updatePorcentaje(int $actividadId, int $porcentaje): Actividad
    {
        $actividad = Actividad::with(['aplicativo'])->findOrFail($actividadId);
        $actividad->porcentaje = $porcentaje;
        $actividad->save();

        return $actividad->load(['area', 'gerenciasColaboradoras']);
    }

    /**
     * Resumen global de métricas del Project Manager para todos los aplicativos.
     */
    public function getResumenGeneral(): Collection
    {
        return Aplicativo::with('gerencia')->get()->map(function ($app) {
            return [
                'aplicativo_id' => $app->id,
                'nombre_aplicativo' => $app->nombre_aplicativo,
                'alias' => $app->alias,
                'gerencia' => $app->gerencia?->sigla ?: ($app->gerencia?->nombre_gerencia ?? 'No asignada'),
                'gerencia_sigla' => $app->gerencia?->sigla,
                'gerencia_nombre' => $app->gerencia?->nombre_gerencia,
                'responsable' => $app->responsable,
                'avance_total' => $app->avance_total,
                'avance_pendiente' => $app->avance_pendiente,
                'metricas' => $app->metricas_actividades,
            ];
        });
    }
}

