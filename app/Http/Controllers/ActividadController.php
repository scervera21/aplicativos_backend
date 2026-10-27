<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRequest;
use Illuminate\Http\Request;
use App\Models\Actividad;

class ActividadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:ver.actividades')->only(['index', 'show']);
        $this->middleware('permission:gestionar.actividades')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $actividades = Actividad::with(['aplicativo', 'area', 'gerenciasColaboradoras'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $actividades
        ]);
    }

    public function store(ActividadRequest $request)
    {
        $validated = $request->validated();
        
        $gerenciasColaboradoras = $validated['gerencias_colaboradoras'] ?? [];
        unset($validated['gerencias_colaboradoras']);

        $actividad = Actividad::create($validated);

        if (!empty($gerenciasColaboradoras)) {
            $actividad->gerenciasColaboradoras()->sync($gerenciasColaboradoras);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Actividad creada exitosamente',
            'data' => $actividad->load(['area', 'gerenciasColaboradoras']),
            'nuevo_avance_aplicativo' => $actividad->aplicativo->avance_total
        ], 201);
    }

    public function show($actividadID)
    {
        $actividad = Actividad::with(['aplicativo', 'area', 'gerenciasColaboradoras'])->find($actividadID);

        if (!$actividad) {
            return response()->json([
                'status' => 'error',
                'message' => 'Actividad no encontrada'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $actividad
        ]);
    }

    public function update(ActividadRequest $request, $actividadID)
    {
        $actividad = Actividad::find($actividadID);

        if (!$actividad) {
            return response()->json([
                'status' => 'error',
                'message' => 'Actividad no encontrada'
            ], 404);
        }

        $validated = $request->validated();
        
        $gerenciasColaboradoras = $validated['gerencias_colaboradoras'] ?? null;
        unset($validated['gerencias_colaboradoras']);

        $actividad->update($validated);

        if (!empty($gerenciasColaboradoras)) {
            $actividad->gerenciasColaboradoras()->sync($gerenciasColaboradoras);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Actividad actualizada exitosamente',
            'data' => $actividad->load(['area', 'gerenciasColaboradoras']),
            'nuevo_avance_aplicativo' => $actividad->aplicativo->avance_total
        ]);
    }

    public function destroy($actividadID)
    {
        $actividad = Actividad::find($actividadID);

        if (!$actividad) {
            return response()->json([
                'status' => 'error',
                'message' => 'Actividad no encontrada'
            ], 404);
        }

        $aplicativo = $actividad->aplicativo;
        $actividad->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Actividad eliminada exitosamente',
            'nuevo_avance_aplicativo' => $aplicativo->avance_total
        ]);
    }
}
