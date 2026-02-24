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
                'nullable',
                'string',
                'max:17',
                Rule::unique('productos', 'codigo_e')->ignore($productoId)->whereNotNull('codigo_e'),
            ],
            'codigo_p' => [
                'nullable',
                'string',
                'max:17',
                Rule::unique('productos', 'codigo_p')->ignore($productoId)->whereNotNull('codigo_p'),
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
            'codigo_e.unique'      => 'Este código Externo ya está registrado en otro producto',
            'codigo_p.unique'      => 'Este código de Producto (inerno) ya está registrado en otro producto',
            'precio_lista.regex'   => 'El precio debe tener máximo 7 enteros y 3 decimales',
            'stock.min'            => 'El stock no puede ser negativo',
        ];
    }

    /**
     * Convierte strings vacíos de código en null antes de validar
     * para que la regla nullable funcione correctamente.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'codigo_e' => $this->codigo_e ?: null,
            'codigo_p' => $this->codigo_p ?: null,
        ]);
    }
}
