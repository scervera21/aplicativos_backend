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
        $aplicativos = Aplicativo::all();
        return response()->json([
            "message" => 'Datos obtenidos exitosamente',
            "data" => $aplicativos
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AplicativoRequest $request)
    {

        if(!auth()->user()->can('crear_aplicativos')) {
            return response()->json([
                "message" => 'No tiene permiso para realizar esta acción',
            ], 403);
        }

        $user = auth()->user();
        $validated = $request->validated();

        $aplicativo = Aplicativo::create($validated);

        return response()->json([
            "message" => 'Aplicativo guardado exitosamente',
            "data" => $aplicativo,
            "user" => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $aplicativo = Aplicativo::find($id);
        if (!$aplicativo) {
            return response()->json([
                "message" => 'Aplicativo no encontrado',
            ], 404);
        }
        return response()->json([
            "message" => 'Aplicativo obtenido exitosamente',
            "data" => $aplicativo
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AplicativoRequest $request, $id)
    {

        if(!auth()->user()->can('editar_aplicativos')) {
            return response()->json([
                "message" => 'No tiene permiso para realizar esta acción',
            ], 403);
        }

        $aplicativo = Aplicativo::findOrFail($id);
        
        if (!$aplicativo) {
            return response()->json([
                "message" => 'Aplicativo no encontrado',
            ], 404);
        }

        $aplicativo->update($request->validated());

        return response()->json([
            "message" => 'Aplicativo actualizado exitosamente',
            "data" => $aplicativo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        if(!auth()->user()->can('eliminar_aplicativos')) {
            return response()->json([
                "message" => 'No tiene permiso para realizar esta acción',
            ], 403);
        }

        $aplicativo = Aplicativo::findOrFail($id);
        $aplicativo->delete();

        return response()->json([
            "message" => 'Aplicativo eliminado exitosamente',
        ], 200);

    }
}
