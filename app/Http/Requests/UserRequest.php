<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
    public function rules(): array
    {
        // Solo ignorar el id si estamos editando un registro existente
        $id = $this->route('id') ?? null;

        return [
            'username' => ['required', 'string', 'max:20', Rule::unique('security.users', 'username')->ignore($id)],
            'first_name' => ['required_if:username', 'string'],
            'last_name' => ['required_if:first_name', 'string'],
            'email' => ['required', 'string', 'email', Rule::unique('security.users', 'email')->ignore($id)],
            'password' => [Password::defaults()],
            'gerencia_id' => ['required', 'exists:public.gerencias,id'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:security.roles'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'El nombre de usuario ya existe',
            'username.max' => 'El nombre de usuario debe tener como maximo 20 caracteres',
            'first_name.required_if' => 'El nombre es obligatorio',
            'last_name.required_if' => 'El apellido es obligatorio',
            'email.required' => 'El correo electronico es obligatorio',
            'email.email' => 'El correo electronico debe ser valido',
            'email.unique' => 'El correo electronico ya existe',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener como minimo 6 caracteres',
            'password.max' => 'La contraseña debe tener como maximo 25 caracteres',
            'password.letters' => 'La contraseña debe tener al menos una letra',
            'password.numbers' => 'La contraseña debe tener al menos un numero',
            'password.symbols' => 'La contraseña debe tener al menos un simbolo',
            'password.uncompromised' => 'La contraseña es debil o ha sido comprometida',
            'gerencia_id.required' => 'La gerencia es obligatoria',
            'gerencia_id.exists' => 'La gerencia no existe',
            'roles.required' => 'El rol es obligatorio',
            'roles.min' => 'Debe seleccionar al menos un rol',
            'roles.*.exists' => 'El rol no existe',
        ];
    }
}
