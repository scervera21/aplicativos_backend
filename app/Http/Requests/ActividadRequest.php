<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadRequest extends FormRequest
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

        // Obtenemos el ID de la actividad de la ruta en caso de ser una actualización (PUT/PATCH)
        $actividadId = $this->route('actividadID');

        return [
            'nombre_actividad' => ['required', 'string', 'max:150', 'unique:public.actividades,nombre_actividad'.($actividadId ? ','.$actividadId : '')],
            'aplicativo_id' => [$isPost ? 'required' : 'sometimes', 'exists:public.aplicativos,id'],
            'area_id' => [$isPost ? 'required' : 'sometimes', 'exists:public.areas,id'],
            'prioridad' => ['required', 'in:BAJA,MEDIA,ALTA'],
            'porcentaje' => ['sometimes', 'integer', 'between:0,100'],
            'fecha_creacion' => ['sometimes', 'date', 'before_or_equal:fecha_culminacion'],
            'fecha_culminacion' => ['nullable', 'date', 'after_or_equal:fecha_creacion'],
            'estado' => [$isPost ? 'required' : 'sometimes', 'in:PENDIENTE,ATRASADO,CULMINADO'],
            'completado' => [$isPost ? 'required' : 'sometimes', 'boolean'],
            'comentarios' => ['nullable', 'string'],
            'gerencias_colaboradoras' => ['nullable', 'array'],
            'gerencias_colaboradoras.*' => ['nullable', 'exists:gerencias,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_actividad.required' => 'El nombre de la actividad es obligatorio.',
            'nombre_actividad.unique' => 'El nombre de la actividad ya existe.',
            'nombre_actividad.max' => 'El nombre de la actividad debe tener como máximo 150 caracteres.',
            'aplicativo_id.required' => 'El aplicativo es obligatorio.',
            'aplicativo_id.exists' => 'El aplicativo seleccionado no existe.',
            'area_id.required' => 'El area es obligatoria.',
            'area_id.exists' => 'El area seleccionada no existe.',
            'prioridad.required' => 'La prioridad es obligatoria.',
            'porcentaje.required' => 'El porcentaje es obligatorio.',
            'porcentaje.integer' => 'El porcentaje debe ser un número entero.',
            'porcentaje.between' => 'El porcentaje debe estar entre 0 y 100.',
            'fecha_creacion.required' => 'La fecha de creación es obligatoria.',
            'fecha_creacion.date' => 'La fecha de creación debe ser una fecha válida.',
            'fecha_creacion.before_or_equal' => 'La fecha de creación debe ser anterior o igual a la fecha de culminación.',
            'fecha_culminacion.date' => 'La fecha de culminación debe ser una fecha válida.',
            'fecha_culminacion.after_or_equal' => 'La fecha de culminación debe ser posterior o igual a la fecha de creación.',
            'estado.required' => 'El estatus es obligatorio.',
            'gerencias_colaboradoras.array' => 'Las gerencias colaboradoras deben ser un array.',
            'gerencias_colaboradoras.*.exists' => 'La gerencia seleccionada no existe.',
        ];
    }
}
