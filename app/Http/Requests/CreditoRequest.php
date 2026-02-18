<?php

/**
 * REQUEST: CreditoRequest.php
 * Ubicación: app/Http/Requests/CreditoRequest.php
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aprobacion' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'aprobacion.required' => 'El estado de aprobación es obligatorio',
            'aprobacion.boolean' => 'El estado de aprobación debe ser verdadero o falso',
        ];
    }
}
