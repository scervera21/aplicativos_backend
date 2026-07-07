<?php

namespace App\Http\Controllers;

use App\Models\Aplicativo;
use Illuminate\Http\Request;
use App\Http\Requests\AplicativoRequest;
use Illuminate\View\View;

class AplicativoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @phpstan-ignore-next-line
     */

    public function index(Request $request): View
    {

        $perPage = $request->get('perPage', 5);  // Define el numero de registros por pagina

        $aplicativos = Aplicativo::latest('updated_at')->paginate($perPage)->withQueryString();

        return view('aplicativos.index', ['aplicativos' => $aplicativos]);
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

        // Forma 1

        /*
        $request() : Es una instancia del Controlador AplicativoRequest que contiene todos los datos de la peticion 
        $user() : Es una instancia del Modelo User que contiene todos los datos del usuario autenticado
        $aplicativos() : Es una instancia del Modelo Aplicativo que contiene todos los datos del aplicativo
        $validated() : Retorna un array con los datos validados 
        */

        $request->user()->aplicativos()->create($request->validated());

        return redirect()->route('aplicativos.index')->with('status', 'Guardado exitosamente');

        // Forma 2

        /*

            $request->user()->aplicativos()->create([

                // El $request->get() realiza la peticion de los datos que vienen del formulario por el metodo post y se guardan en la variable $request

                'aplicativo' => $request->get('aplicativo'),
                'tipo_software' => $request->get('tipo_software'),
                'fecha_inicio' => $request->get('fecha_inicio'),
                'fecha_final' => $request->get('fecha_final'),
                'estatus' => $request->get('estatus'),
                'pap' => $request->get('pap'),
                // Si no viene en el $request->get, por defecto Laravel retorna null
                'pap_estatus' => $request->get('pap_estatus'),
            ]); 

        */

        //  Forma 3

        /*
            Aplicativo::create([

                // El $request->get() realiza la peticion de los datos que vienen del formulario por el metodo post y se guardan en la variable $request

                'aplicativo' => $request->get('aplicativo'),
                'tipo_software' => $request->get('tipo_software'),
                'fecha_inicio' => $request->get('fecha_inicio'),
                'fecha_final' => $request->get('fecha_final'),
                'estatus' => $request->get('estatus'),
                'pap' => $request->get('pap'),

                // Si no viene en el $request->get, por defecto Laravel retorna null
                'pap_estatus' => $request->get('pap_estatus'),
                'user_id' => auth()->id(),  // auth()->id() devuelve el id del usuario autenticado
            ]);        
        */

    }

    /**
     * Display the specified resource.
     */
    public function show(Aplicativo $aplicativo)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $aplicativo = Aplicativo::findOrFail($id);    // findOrFail() es un método de Eloquent que busca un registro por su clave primaria 
        return view('aplicativos.edit', compact('aplicativo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AplicativoRequest $request, $id)
    {
        $aplicativo = Aplicativo::findOrFail($id);
        $aplicativo->update($request->validated());
        return redirect()->route('aplicativos.index')->with('info', 'Registro actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        /*

        $aplicativo = Aplicativo::findOrFail($id);
        $aplicativo->delete();
        return redirect()->route('aplicativos.index')->with('success', 'Aplicativo eliminado exitosamente');

        */

        Aplicativo::destroy($id); // destroy() es un método de Eloquent que elimina un registro por su clave primaria
        return redirect()->route('aplicativos.index')->with('success', 'Aplicativo eliminado exitosamente'); // with() es un método de Eloquent que retorna un mensaje de éxito

    }
}
