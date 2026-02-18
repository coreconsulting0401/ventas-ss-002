<?php

/**
 * REQUEST: ProductoRequest.php
 * Ubicación: app/Http/Requests/ProductoRequest.php
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productoId = $this->route('producto');

        return [
            'codigo_e' => [
                'required',
                'string',
                'max:12',
                Rule::unique('productos', 'codigo_e')->ignore($productoId),
            ],
            'codigo_p' => [
                'required',
                'string',
                'max:12',
                Rule::unique('productos', 'codigo_p')->ignore($productoId),
            ],
            'nombre' => 'required|string|max:150',
            'marca' => 'required|string|max:50',
            'ubicacion' => 'required|string|max:10',
            'precio_lista' => 'required|numeric|min:0|regex:/^\d{1,7}(\.\d{1,3})?$/',
            'stock' => 'required|integer|min:0',
            'descuento_id' => 'nullable|exists:descuentos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo_e.required' => 'El código E es obligatorio',
            'codigo_e.unique' => 'Este código E ya está registrado',
            'codigo_p.required' => 'El código P es obligatorio',
            'codigo_p.unique' => 'Este código P ya está registrado',
            'precio_lista.regex' => 'El precio debe tener máximo 7 enteros y 3 decimales',
            'stock.min' => 'El stock no puede ser negativo',
        ];
    }
}
