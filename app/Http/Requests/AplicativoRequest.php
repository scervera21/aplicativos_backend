<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AplicativoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isPost = $this->isMethod('POST');

        // Obtenemos el ID del aplicativo de la ruta en caso de ser una actualización (PUT/PATCH)
        $appId = $this->route('aplicativoID');

        return [
            'nombre_aplicativo' => ['required', 'string', 'max:80', 'unique:public.aplicativos,nombre_aplicativo' . ($appId ? ',' . $appId : '')],
            'alias' => ['nullable', 'string', 'max:8'],
            'responsable' => ['nullable', 'string', 'max:50'],
            'pap' => ['boolean'],
            'estatus' => ['string', 'max:30'],
            'descripcion' => ['nullable', 'string', 'max:200'],
            'lenguajes_frontend' => ['nullable', 'string', 'max:50'],
            'lenguajes_backend' => ['nullable', 'string', 'max:50'],
            'bases_datos' => ['nullable', 'string', 'max:50'],
            'fecha_inicio' => ['nullable', 'date', 'before:fecha_fin'],
            'fecha_fin' => ['nullable', 'date', 'after:fecha_inicio'],
            'observaciones' => ['nullable', 'string'],
            'gerencia_id' => [$isPost ? 'required' : 'sometimes', 'exists:gerencias,id']
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_aplicativo.required' => 'El nombre de la aplicación es obligatorio.',
            'nombre_aplicativo.max' => 'El nombre de la aplicación debe tener como máximo 80 caracteres.',
            'nombre_aplicativo.unique' => 'El nombre de la aplicación ya existe.',
            'alias.max' => 'El alias de la aplicación debe tener como máximo 8 caracteres.',
            'responsable.max' => 'El responsable de la aplicación debe tener como máximo 50 caracteres.',
            'estatus.max' => 'El estatus de la aplicación debe tener como máximo 30 caracteres.',
            'descripcion.max' => 'La descripción de la aplicación debe tener como máximo 200 caracteres.',
            'lenguajes_frontend.max' => 'Los lenguajes de frontend de la aplicación deben tener como máximo 50 caracteres.',
            'lenguajes_backend.max' => 'Los lenguajes de backend de la aplicación deben tener como máximo 50 caracteres.',
            'bases_datos.max' => 'Las bases de datos de la aplicación deben tener como máximo 50 caracteres.',
            'fecha_inicio.before' => 'La fecha de inicio debe ser anterior a la fecha de fin.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'gerencia_id.exists' => 'La gerencia seleccionada no existe.',
        ];
    }
}
