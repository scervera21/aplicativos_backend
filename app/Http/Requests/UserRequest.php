<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use App\Models\User;

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
        $userID = $this->route('userID');

        return [
            'username' => [
                $isPost ? 'required' : 'sometimes',  // Agregar si el usuario no existe o es el mismo usuario (id) para evitar conflicto con el id 
                'string',
                'max:30',
                Rule::unique(User::class, 'username')->ignore($userID)
            ],
            'first_name' => [$isPost ? 'required' : 'sometimes', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', 'max:30'],
            'last_name'      => [$isPost ? 'required' : 'sometimes', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', 'max:30'],
            'email'         => [
                $isPost ? 'required' : 'sometimes',
                'email',
                'max:100',
                Rule::unique(User::class, 'email')->ignore($userID)
            ],
            'password'      => [$isPost ? 'required' : 'nullable', Password::default()],
            'gerencia_id'   => [$isPost ? 'required' : 'sometimes', 'exists:gerencias,id'], 
            'is_active'     => 'sometimes|boolean',
            'roles'         => [$isPost ? 'required' : 'sometimes', 'array'],
            'roles.*'       => 'exists:roles,name'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.unique' => 'El nombre de usuario ya existe.',
            'username.max' => 'El nombre de usuario debe tener como máximo 30 caracteres.',
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.regex' => 'El nombre solo debe contener letras, espacios y acentos.',
            'first_name.max' => 'El nombre debe tener como máximo 30 caracteres.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.regex' => 'El apellido solo debe contener letras, espacios y acentos.',
            'last_name.max' => 'El apellido debe tener como máximo 30 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya existe.',
            'email.email' => 'El correo electrónico debe ser un correo electrónico válido.',
            'email.max' => 'El correo electrónico debe tener como máximo 100 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'gerencia_id.required' => 'La gerencia es obligatoria.',
            'gerencia_id.exists' => 'La gerencia seleccionada no existe.',
            'roles.required' => 'Los roles son obligatorios.',
            'roles.array' => 'Los roles deben ser un array.',
            'roles.*.exists' => 'El rol seleccionado no existe.',
        ];
    }
}
