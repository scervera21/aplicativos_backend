<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        $isPost = $this->isMethod('POST');
        // Obtenemos el ID del usuario de la ruta en caso de ser una actualización (PUT/PATCH)

        return [
            'nombre_usuario' => [
                $isPost ? 'required' : 'sometimes',
                'string',
                'max:30',
                'unique:usuarios,nombre_usuario'
            ],
            'primer_nombre' => [$isPost ? 'required' : 'sometimes', 'string', 'max:30'],
            'apellido'      => [$isPost ? 'required' : 'sometimes', 'string', 'max:30'],
            'email'         => [
                $isPost ? 'required' : 'sometimes',
                'email',
                'max:100',
                'unique:usuarios,email' . ($userID ? ',' . $userID : '')
            ],
            'password'      => [$isPost ? 'required' : 'sometimes', 'string', 'min:6'],
            'gerencia_id'   => [$isPost ? 'required' : 'sometimes', 'exists:gerencias,id'],
            'activo'        => 'sometimes|boolean',
            'rol'           => 'sometimes|exists:roles,name'
        ];
    }

    public function messages()
    {
        
    }
}
