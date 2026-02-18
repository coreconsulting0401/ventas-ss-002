<?php

/**
 * REQUEST: ContactoRequest.php
 * Ubicación: app/Http/Requests/ContactoRequest.php
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contactoId = $this->route('contacto');

        return [
            'dni' => [
                'required',
                'string',
                'size:8',
                'regex:/^[0-9]{8}$/',
                Rule::unique('contactos', 'dni')->ignore($contactoId),
            ],
            'nombre' => 'required|string|max:200',
            'telefono' => 'required|string|max:15|regex:/^[0-9]+$/',
            'email' => 'required|email|max:255',
            'cargo' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'dni.required' => 'El DNI es obligatorio',
            'dni.size' => 'El DNI debe tener exactamente 8 dígitos',
            'dni.regex' => 'El DNI solo debe contener números',
            'dni.unique' => 'Este DNI ya está registrado',
            'nombre.required' => 'El nombre es obligatorio',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono solo debe contener números',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser una dirección válida',
            'cargo.required' => 'El cargo es obligatorio',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio',
            'apellido_materno.required' => 'El apellido materno es obligatorio',
        ];
    }
}
