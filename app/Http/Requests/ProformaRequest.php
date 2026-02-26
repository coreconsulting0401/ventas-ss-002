<?php

/**
 * FORM REQUEST: ProformaRequest.php
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
            'cliente_id'   => 'required|exists:clientes,id',

            // "principal" es un valor especial permitido; de lo contrario debe existir en direccions
            'direccion_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && $value !== 'principal') {
                        if (!\App\Models\Direccion::find($value)) {
                            $fail('La dirección seleccionada no es válida.');
                        }
                    }
                },
            ],

            // El contacto debe pertenecer al cliente seleccionado (validación cruzada)
            'contacto_id'  => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!$value) return;

                    $contacto  = \App\Models\Contacto::find($value);
                    if (!$contacto) { $fail('El contacto seleccionado no existe.'); return; }

                    $clienteId = $this->input('cliente_id');
                    if ($clienteId) {
                        $pertenece = $contacto->clientes()
                            ->where('clientes.id', $clienteId)
                            ->exists();
                        if (!$pertenece) {
                            $fail('El contacto seleccionado no pertenece al cliente indicado.');
                        }
                    }
                },
            ],

            'transaccion_id' => 'nullable|exists:transaccions,id',
            'temperatura_id' => 'nullable|exists:temperaturas,id',
            'estado_id'      => 'nullable|exists:estados,id',
            'nota'           => 'nullable|string|max:500',
            'orden'          => 'nullable|string|max:100',
            'fecha_creacion' => 'required|date',
            'fecha_fin'      => 'required|date|after_or_equal:fecha_creacion',
            'moneda'         => 'required|in:Dolares,Soles',
            'sub_total'      => 'required|numeric|min:0',
            'monto_igv'      => 'required|numeric|min:0',
            'total'          => 'required|numeric|min:0',

            'productos'                     => 'required|array|min:1',
            'productos.*.id'                => 'required|exists:productos,id',
            'productos.*.cantidad'          => 'required|integer|min:1',
            'productos.*.precio_unitario'   => 'required|numeric|min:0',
            'productos.*.descuento_cliente' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required'      => 'Debe seleccionar un cliente.',
            'fecha_creacion.required'  => 'La fecha de creación es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha fin no puede ser anterior a la fecha de creación.',
            'productos.required'       => 'Debe agregar al menos un producto.',
            'productos.min'            => 'Debe agregar al menos un producto.',
        ];
    }
}
