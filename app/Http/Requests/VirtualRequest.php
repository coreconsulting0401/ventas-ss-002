<?php
/**
 * REQUEST: VirtualRequest.php
 * Ubicación: app/Http/Requests/VirtualRequest.php
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class VirtualRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        $virtualId = $this->route('virtual');

        return [
            'nombre' => 'required|string|max:150',
            'precio_compra' => [
                'required',
                'string',
                'max:12',
                Rule::unique('virtuals', 'precio_compra')->ignore($virtualId),
            ],
            'precio_venta' => 'required|numeric|min:0|regex:/^\d{1,7}(\.\d{1,3})?$/',
            'marca' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'proveedores' => 'nullable|array',
            'proveedores.*' => 'exists:proveedors,id',
        ];
    }

    public function messages(): array
    {
        return [
            'precio_compra.unique' => 'Este código de precio de compra ya está registrado',
            'precio_venta.regex' => 'El precio debe tener máximo 7 enteros y 3 decimales',
        ];
    }
}
