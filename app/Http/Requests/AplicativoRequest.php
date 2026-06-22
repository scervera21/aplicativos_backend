<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'pap' => $this->has('pap'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            // bail : Se detiene al producirse el primer fallo de validacion
            
            'aplicativo' => [
                'bail',
                'required',
                Rule::unique('aplicativos', 'aplicativo')->ignore($this->route('id')), // Se ignora el id del registro actual para que no se compare consigo mismo
                'string',
                'between:4,80'
            ],     
            'tipo_software' => ['bail','nullable','string','between:4,30'],
            'fecha_inicio' => ['bail', 'required', 'date'],
            'fecha_final' => ['bail', 'nullable', 'date', 'after:fecha_inicio'],
            'estatus' => ['bail', 'required'],
            'pap' => ['bail', 'nullable','boolean'],
            'pap_estatus' => ['bail', 'required_if:pap,true', 'between:4,60',],
        ];
    }

    public function messages(): array
    {
        return [
            'aplicativo.required' => 'El nombre del aplicativo es requerido',
            'aplicativo.unique' => 'Este aplicativo ya esta registrado',
            'aplicativo.between' => 'El nombre del aplicativo debe tener entre 4 y 80 caracteres',
            'aplicativo.string' => 'El nombre del aplicativo debe ser una cadena de texto',
            'tipo_software.between' => 'El tipo de software debe tener entre 4 y 30 caracteres',
            'tipo_software.string' => 'El tipo de software debe ser una cadena de texto',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_final.after' => 'La fecha final debe ser mayor a la fecha inicial',
            'estatus.required' => 'El estatus es obligatorio',
            'pap_estatus.required_if' => 'El estatus PAP es obligatorio',
            'pap_estatus.between' => 'El estatus PAP debe tener entre 4 y 60 caracteres',
        ];
    }
}
