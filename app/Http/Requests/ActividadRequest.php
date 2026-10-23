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
        return [
            'actividad' => ['required','string'],
            'creado_el' => ['required','date'],
            'culminado_el' => ['required','date'],
            'area_id' => ['required','exists:public.areas,id'],
            'prioridad' => ['required','in:baja,media,alta'],
            'estado' => ['required','in:Pendiente,Finalizado,Atrasado'],
            'completado' => ['required'],
            'comentarios' => ['required','string'],
            'aplicativo_id' => ['bail','required','exists:public.aplicativos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'actividad.required' => 'La actividad es obligatoria',
            'creado_el.required' => 'La fecha de creacion es obligatoria',
            'creado_el.date' => 'La fecha de creacion debe ser una fecha valida',
            'culminado_el.required' => 'La fecha de culminacion es obligatoria',
            'culminado_el.date' => 'La fecha de culminacion debe ser una fecha valida',
            'area_id.required' => 'El area es obligatoria',
            'area_id.exists' => 'El area no existe',
            'prioridad.required' => 'La prioridad es obligatoria',
            'prioridad.in' => 'La prioridad debe ser baja, media o alta',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser pendiente, en progreso o completado',
            'completado.required' => 'El estado de completado es obligatorio',
            'completado.boolean' => 'El estado de completado debe ser verdadero o falso',
            'comentarios.required' => 'Los comentarios son obligatorios',
            'aplicativo_id.required' => 'El aplicativo es obligatorio',
            'aplicativo_id.exists' => 'El aplicativo no existe',
        ];
    }
}
