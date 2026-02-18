<?php

/**
 * REQUEST: DireccionRequest.php
 * Ubicación: app/Http/Requests/DireccionRequest.php
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DireccionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'direccion' => 'required|string|max:250',
            'cliente_id' => 'required|exists:clientes,id',
        ];
    }
}
