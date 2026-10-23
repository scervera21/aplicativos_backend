<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ActividadRequest;
use App\Models\Actividad;

class ActividadController extends Controller
{

    public function index(Request $request)
    {
        try {
            $actividades = Actividad::all();
            return response()->json([
                'aplicativos' => $actividades
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los aplicativos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(ActividadRequest $request)
    {
        try {
            $actividad = Actividad::create($request->validated());
            return response()->json([
                'message' => 'Actividad creada exitosamente',
                'aplicativo' => $actividad
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al guardar la actividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Actividad $actividad)
    {
        try {
            $actividad = Actividad::find($actividad);
            return response()->json([
                'aplicativo' => $actividad
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la actividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(ActividadRequest $request, $actividadId)
    {
        try {
            $actividad = Actividad::find($actividadId);
            $actividad->update($request->validated());
            return response()->json([
                'message' => 'Actividad actualizada exitosamente',
                'aplicativo' => $actividad
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la actividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($actividadId)
    {
        try {
            $actividad = Actividad::find($actividadId);
            $actividad->delete();
            return response()->json([
                'message' => 'Actividad eliminada exitosamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la actividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
