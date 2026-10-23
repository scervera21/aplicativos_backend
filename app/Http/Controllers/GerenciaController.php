<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gerencia;
use Illuminate\Support\Facades\Validator;

class GerenciaController extends Controller
{

    public function index()
    {
        try {
            $gerencias = Gerencia::all();
            return response()->json([
                'gerencias' => $gerencias
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las gerencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
                'descripcion' => 'required',
                'estado' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $gerencia = Gerencia::create($request->all());
            return response()->json([
                'message' => 'Gerencia creada exitosamente',
                'gerencia' => $gerencia
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar la gerencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $gerenciaId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
                'descripcion' => 'required',
                'estado' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $gerencia = Gerencia::find($gerenciaId);

            if (!$gerencia) {
                return $this->errorResponse('Gerencia no encontrada', 404);
            }

            $gerencia->update($request->all());
            return response()->json([
                'message' => 'Gerencia actualizada exitosamente',
                'gerencia' => $gerencia
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la gerencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($gerenciaId)
    {
        try {
            $gerencia = Gerencia::find($gerenciaId);
            $gerencia->delete();
            return response()->json([
                'message' => 'Gerencia eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la gerencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
