<?php

/**
 * REQUEST: CambioRequest.php
 * Ubicación: app/Http/Requests/CambioRequest.php
 *
 * IMPORTANTE: solo valida el campo "incremento" porque es el único
 * que el usuario puede modificar. Los demás campos los gestiona
 * el comando ConsultarTipoCambio (solo lectura para el usuario).
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CambioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'incremento' => [
                'required',
                'numeric',
                'min:0',
                'max:1',                        // máximo S/. 1.00 de incremento
                'regex:/^\d{1,2}(\.\d{1,4})?$/', // máx. 4 decimales
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'incremento.required' => 'El incremento es obligatorio.',
            'incremento.numeric'  => 'El incremento debe ser un número.',
            'incremento.min'      => 'El incremento no puede ser negativo.',
            'incremento.max'      => 'El incremento no puede superar S/. 1.00.',
            'incremento.regex'    => 'Máximo 4 decimales (ej: 0.0200).',
        ];
    }
}
