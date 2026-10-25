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
    protected function prepareForValidation()
    {
        $this->merge([
            'pap' => (bool) $this->pap,
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
            'aplicativo' => [
                'required',
                Rule::unique('aplicativos', 'aplicativo')->ignore($this->route('id')), // Se ignora el id del registro actual para que no se compare consigo mismo
                'string',
                'between:4,80'
            ],
            'alias' => ['nullable', 'string', 'between:2,8'],
            'descripcion' => ['nullable', 'string', 'max:100'],
            'estatus' => ['nullable', 'string', 'max:20'],
            'pap' => ['boolean'],
            'responsable' => ['nullable', 'exists:security.users,id'],
            'gerencia_id' => ['nullable', 'exists:public.gerencias,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'aplicativo.required' => 'El nombre del aplicativo es requerido',
            'aplicativo.unique' => 'Este aplicativo ya esta registrado',
            'aplicativo.between' => 'El nombre del aplicativo debe tener entre 4 y 80 caracteres',
            'aplicativo.string' => 'El nombre del aplicativo debe ser una cadena de texto',
            'alias.string' => 'El alias del aplicativo debe ser una cadena de texto',
            'alias.between' => 'El alias del aplicativo debe tener entre 2 y 8 caracteres',
            'descripcion.string' => 'La descripcion del aplicativo debe ser una cadena de texto',
            'descripcion.max' => 'La descripcion del aplicativo debe tener como maximo 100 caracteres',
            'pap.boolean' => 'El campo PAP debe ser verdadero o falso',
            'responsable.exists' => 'El responsable no existe',
            'gerencia_id.exists' => 'La gerencia no existe',
        ];
    }
}
