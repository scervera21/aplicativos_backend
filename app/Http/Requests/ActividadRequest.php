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
            'actividad' => ['bail','required','string'],
            'creado_el' => ['bail','required','date'],
            'culminado_el' => ['bail','required','date'],
            'area_id' => ['bail','required','exists:public.areas,id'],
            'prioridad' => ['bail','required','in:baja,media,alta'],
            'estado' => ['bail','required','in:Pendiente,Finalizado,Atrasado'],
            'completado' => ['bail','required'],
            'comentarios' => ['bail','required','string'],
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

    public function attributes(): array
    {
        return [
            'actividad' => 'actividad',
            'creado_el' => 'fecha de creacion',
            'culminado_el' => 'fecha de culminacion',
            'area_id' => 'area',
            'prioridad' => 'prioridad',
            'estado' => 'estado',
            'completado' => 'estado de completado',
            'comentarios' => 'comentarios',
            'aplicativo_id' => 'aplicativo',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->creado_el > $this->culminado_el) {
                $validator->errors()->add('creado_el', 'La fecha de creacion debe ser menor a la fecha de culminacion');
            }
        });
    }
}
