<?php

namespace App\Http\Controllers;

use App\Services\ProjectManagerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectManagerController extends Controller
{
    public function __construct(protected ProjectManagerService $pmService)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:ver.project-manager')->only(['show', 'resumen']);
        $this->middleware('permission:gestionar.project-manager')->only(['updateChecklist', 'updatePorcentaje']);
    }

    /**
     * Muestra la estructura del Project Manager para un aplicativo específico.
     * Accesible tanto para Administrador como para Colaborador (permission: ver.project-manager).
     */
    public function show(int $aplicativoID): JsonResponse
    {
        $data = $this->pmService->getAplicativoProjectData($aplicativoID);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Resumen de todos los aplicativos con sus avances y métricas.
     * Accesible para ver.project-manager.
     */
    public function resumen(): JsonResponse
    {
        $resumen = $this->pmService->getResumenGeneral();

        return response()->json([
            'status' => 'success',
            'data' => $resumen
        ]);
    }

    /**
     * Actualiza el checklist de una actividad (marcar completada o pendiente).
     * Solo para administradores / roles con permission: gestionar.project-manager.
     */
    public function updateChecklist(Request $request, int $actividadID): JsonResponse
    {
        $request->validate([
            'completado' => 'required|boolean'
        ]);

        $actividad = $this->pmService->toggleChecklist($actividadID, $request->boolean('completado'));

        return response()->json([
            'status' => 'success',
            'message' => 'Checklist de la actividad actualizado exitosamente',
            'data' => [
                'actividad' => $actividad,
                'nuevo_avance_aplicativo' => $actividad->aplicativo ? $actividad->aplicativo->avance_total : 0.0,
                'nuevo_avance_pendiente' => $actividad->aplicativo ? $actividad->aplicativo->avance_pendiente : 100.0,
            ]
        ]);
    }

    /**
     * Modifica el porcentaje percentil de una actividad desde el Project Manager.
     * Solo para administradores / roles con permission: gestionar.project-manager.
     */
    public function updatePorcentaje(Request $request, int $actividadID): JsonResponse
    {
        $request->validate([
            'porcentaje' => 'required|integer|min:0|max:100'
        ]);

        $actividad = $this->pmService->updatePorcentaje($actividadID, (int) $request->porcentaje);

        return response()->json([
            'status' => 'success',
            'message' => 'Porcentaje actualizado exitosamente',
            'data' => [
                'actividad' => $actividad,
                'nuevo_avance_aplicativo' => $actividad->aplicativo ? $actividad->aplicativo->avance_total : 0.0,
                'nuevo_avance_pendiente' => $actividad->aplicativo ? $actividad->aplicativo->avance_pendiente : 100.0,
            ]
        ]);
    }
}

