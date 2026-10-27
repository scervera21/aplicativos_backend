<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gerencia;

class GerenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:ver.gerencias')->only(['index']);
        $this->middleware('permission:gestionar.gerencias')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $gerencias = Gerencia::all();

        return response()->json([
            'status' => 'success',
            'data' => $gerencias
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre_gerencia' => 'required|string|max:80',
            'sigla' => 'required|string|max:10|unique:gerencias,sigla'
        ]);

        $gerencia = Gerencia::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Gerencia registrada exitosamente',
            'data' => $gerencia
        ], 201);
    }

    public function update(Request $request, $gerenciaID)
    {
        $gerencia = Gerencia::find($gerenciaID);

        if (!$gerencia) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gerencia no encontrada'
            ], 404);
        }

        $validatedData = $request->validate([
            'nombre_gerencia' => 'sometimes|string|max:80',
            'sigla' => 'sometimes|string|max:10|unique:gerencias,sigla,' . $gerenciaID
        ]);

        $gerencia->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Gerencia actualizada exitosamente',
            'data' => $gerencia
        ]);
    }

    public function destroy($gerenciaID)
    {
        $gerencia = Gerencia::find($gerenciaID);

        if (!$gerencia) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gerencia no encontrada'
            ], 404);
        }

        $gerencia->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Gerencia eliminada exitosamente'
        ]);
    }
}
