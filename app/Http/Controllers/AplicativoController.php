<?php

namespace App\Http\Controllers;

use App\Models\Aplicativo;
use Illuminate\Http\Request;
use App\Http\Requests\AplicativoRequest;

class AplicativoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:ver.aplicativos')->only(['index', 'show']);
        $this->middleware('permission:gestionar.aplicativos')->only(['store', 'update', 'destroy']);
    }
    
    /**
     * Display a listing of the resource.
     * @phpstan-ignore-next-line
     */

    public function index()
    {
        // Cargamos la relación de gerencia y actividades
        $aplicativos = Aplicativo::with(['gerencia', 'actividades'])->get();

        // Informacion principal de los aplicativos
        $data = $aplicativos->map(function ($app) {
            return [
                'id' => $app->id,
                'nombre_aplicativo' => $app->nombre_aplicativo,
                'alias' => $app->alias,
                'descripcion' => $app->descripcion,
                'responsable' => $app->responsable,
                'gerencia_responsable' => $app->gerencia->nombre_gerencia ?? "No posee",
                'avance_total' => $app->AvanceTotalAplicativo(), // Cálculo del avance total del aplicativo
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AplicativoRequest $request)
    {
        $aplicativo = Aplicativo::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Aplicativo creado exitosamente',
            'data' => $aplicativo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($aplicativoID)
    {
        $aplicativo = Aplicativo::find($aplicativoID);

        if (!$aplicativo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aplicativo no encontrado'
            ], 404);
        }

        // Informacion detallada del aplicativo
        $detalles = $aplicativo->map(function ($app) {
            return [
                'fecha_inicio' => $app->fecha_inicio,
                'fecha_fin' => $app->fecha_fin,
                'pap' => $app->pap,
                'estatus' => $app->estatus,
                'responsable' => $app->responsable,
                'lenguajes_frontend' => $app->lenguajes_frontend,
                'lenguajes_backend' => $app->lenguajes_backend,
                'bases_datos' => $app->bases_datos,
                'observaciones' => $app->observaciones,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'detalle_aplicativo' => $detalles,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AplicativoRequest $request, $aplicativoID)
    {
        $aplicativo = Aplicativo::find($aplicativoID);

        if (!$aplicativo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aplicativo no encontrado'
            ], 404);
        }

        $aplicativo->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Aplicativo actualizado exitosamente',
            'data' => $aplicativo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($aplicativoID)
    {
        $aplicativo = Aplicativo::find($aplicativoID);

        if (!$aplicativo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aplicativo no encontrado'
            ], 404);
        }

        $aplicativo->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Aplicativo eliminado exitosamente'
        ]);
    }
}
