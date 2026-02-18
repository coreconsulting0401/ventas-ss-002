<?php

/**
 * REQUEST: ClienteRequest.php
 * Ubicación: app/Http/Requests/ClienteRequest.php
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente');

        return [
            'ruc' => [
                'required',
                'string',
                'size:11',
                'regex:/^[0-9]{11}$/',
                Rule::unique('clientes', 'ruc')->ignore($clienteId),
            ],
            'razon' => 'required|string|max:250',
            'direccion' => 'required|string|max:200',
            'telefono1' => 'required|string|max:15|regex:/^[0-9]+$/',
            'telefono2' => 'nullable|string|max:15|regex:/^[0-9]+$/',
            'credito_id' => 'nullable|exists:creditos,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'contactos' => 'nullable|array',
            'contactos.*' => 'exists:contactos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ruc.required' => 'El RUC es obligatorio',
            'ruc.size' => 'El RUC debe tener exactamente 11 dígitos',
            'ruc.regex' => 'El RUC solo debe contener números',
            'ruc.unique' => 'Este RUC ya está registrado',
            'razon.required' => 'La razón social es obligatoria',
            'direccion.required' => 'La dirección es obligatoria',
            'telefono1.required' => 'El teléfono 1 es obligatorio',
            'telefono1.regex' => 'El teléfono 1 solo debe contener números',
            'telefono2.regex' => 'El teléfono 2 solo debe contener números',
        ];
    }
}
