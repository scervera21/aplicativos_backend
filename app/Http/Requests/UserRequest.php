<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:20', Rule::unique('users', 'username')],
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email')],
            'password' => ['required', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'El nombre de usuario ya existe',
            'username.max' => 'El nombre de usuario debe tener como maximo 20 caracteres',
            'first_name.required' => 'El nombre es obligatorio',
            'first_name.max' => 'El nombre debe tener como maximo 20 caracteres',
            'last_name.required' => 'El apellido es obligatorio',
            'last_name.max' => 'El apellido debe tener como maximo 20 caracteres',
            'email.required' => 'El correo electronico es obligatorio',
            'email.email' => 'El correo electronico debe ser valido',
            'email.unique' => 'El correo electronico ya existe',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener como minimo 6 caracteres',
            'password.letters' => 'La contraseña debe tener al menos una letra',
            'password.numbers' => 'La contraseña debe tener al menos un numero',
            'password.symbols' => 'La contraseña debe tener al menos un simbolo',
            'password.uncompromised' => 'La contraseña es debil o ha sido comprometida',
        ];
    }
}
