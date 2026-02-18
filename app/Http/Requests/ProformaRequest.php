<?php

/**
 * REQUEST: ProformaRequest.php
 * Ubicación: app/Http/Requests/ProformaRequest.php
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProformaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'transaccion_id' => 'nullable|exists:transaccions,id',
            'temperatura_id' => 'nullable|exists:temperaturas,id',
            'estado_id' => 'nullable|exists:estados,id',
            'nota' => 'nullable|string|max:200',
            'orden' => 'nullable|string|max:20',
            'moneda' => 'required|in:Dolares,Soles',
            'sub_total' => 'required|numeric|min:0',
            'monto_igv' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'fecha_creacion' => [
                'required',
                'date',
                'after_or_equal:' . now()->format('Y-m-d')
            ],
            'fecha_fin' => 'required|date|after_or_equal:fecha_creacion',
            // Productos en la proforma
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'productos.*.descuento_cliente' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required' => 'Debe seleccionar un cliente',
            'moneda.required' => 'La moneda es obligatoria',
            'sub_total.required' => 'El subtotal es obligatorio',
            'monto_igv.required' => 'El IGV es obligatorio',
            'total.required' => 'El total es obligatorio',
            'fecha_creacion.required' => 'La fecha de creación es obligatoria',
            'fecha_creacion.after_or_equal' => 'La fecha de creación no puede ser anterior a hoy',
            'fecha_fin.required' => 'La fecha de fin es obligatoria',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de creación',
            'productos.required' => 'Debe seleccionar al menos un producto',
            'productos.min' => 'Debe seleccionar al menos un producto',
        ];
    }
}
