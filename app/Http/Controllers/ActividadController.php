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

        // Extraer gerencias colaboradoras si vienen en el request
        $gerenciasColaboradoras = $validated['gerencias_colaboradoras'] ?? [];
        unset($validated['gerencias_colaboradoras']);

        $actividad = Actividad::create($validated);

        // Sincronizar tabla pivote de colaboradores
        if (!empty($gerenciasColaboradoras)) {
            $actividad->gerenciasColaboradoras()->sync($gerenciasColaboradoras);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Actividad creada exitosamente',
            'data' => $actividad->load(['area', 'gerenciasColaboradoras']),
            'nuevo_avance_aplicativo' => $actividad->aplicativo->avanceAplicativo
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
            'nuevo_avance_aplicativo' => $actividad->aplicativo->avanceAplicativo
        ]);
    }

    // Metodo para actualizacion de porcentaje en tiempo real

    public function updatePorcentaje(Request $request, $actividadID)
    {
        // Validación estricta del rango permitido para el porcentaje
        $request->validate([
            'porcentaje' => 'required|integer|min:0|max:100'
        ]);

        $actividad = Actividad::find($actividadID);

        if (!$actividad) {
            return response()->json([
                'status' => 'error',
                'message' => 'Actividad no encontrada'
            ], 404);
        }

        // Asignar el porcentaje disparará automáticamente el mutator/lógica del modelo 
        // para cambiar el estado 'completado' a true si llega al 100%.
        $actividad->porcentaje = $request->porcentaje;
        $actividad->save();

        // Obtenemos el aplicativo padre para recalcular el avance total automáticamente
        $aplicativo = $actividad->aplicativo;

        return response()->json([
            'status' => 'success',
            'message' => 'Porcentaje actualizado correctamente',
            'data' => [
                'actividad' => $actividad,
                'nuevo_avance_aplicativo' => $aplicativo ? $aplicativo->avanceAplicativo : 0
            ]
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

        //$aplicativo = $actividad->aplicativo;
        $actividad->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Actividad eliminada exitosamente',
            'nuevo_avance_aplicativo' => $actividad->aplicativo->avanceAplicativo
        ]);
    }
}
