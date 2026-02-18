<?php

/**
 * REQUEST: DescuentoRequest.php
 * Ubicación: app/Http/Requests/DescuentoRequest.php
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DescuentoRequest extends FormRequest
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
            'porcentaje' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'porcentaje.required' => 'El porcentaje es obligatorio',
            'porcentaje.min' => 'El porcentaje no puede ser menor a 0',
            'porcentaje.max' => 'El porcentaje no puede ser mayor a 100',
        ];
    }
}
