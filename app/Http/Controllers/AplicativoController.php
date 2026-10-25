<?php

namespace App\Http\Controllers;

use App\Models\Aplicativo;
use Illuminate\Http\Request;
use App\Http\Requests\AplicativoRequest;

class AplicativoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @phpstan-ignore-next-line
     */

    public function index()
    {
        try {
            $aplicativos = Aplicativo::all();
            return response()->json([
                'aplicativos' => $aplicativos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los aplicativos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AplicativoRequest $request)
    {
        try {
            $aplicativo = Aplicativo::create($request->validated());
            return response()->json([
                'message' => 'Aplicativo creado exitosamente',
                'aplicativo' => $aplicativo
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el aplicativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($aplicativoId)
    {
        try {
            $aplicativo = Aplicativo::find($aplicativoId);
            return response()->json([
                'aplicativo' => $aplicativo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el aplicativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AplicativoRequest $request, $aplicativoId)
    {
        try {
            $aplicativo = Aplicativo::find($aplicativoId);
            $aplicativo->update($request->validated());
            return response()->json([
                'message' => 'Aplicativo actualizado exitosamente',
                'aplicativo' => $aplicativo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el aplicativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($aplicativoId)
    {
        try {
            $aplicativo = Aplicativo::find($aplicativoId);
            $aplicativo->delete();
            return response()->json([
                'message' => 'Aplicativo eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el aplicativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
