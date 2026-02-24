<?php

/**
 * CONTROLADOR: ClienteDireccionesController.php
 * Ubicación: app/Http/Controllers/ClienteDireccionesController.php
 *
 * Devuelve las direcciones de un cliente para los selects dinámicos de proformas.
 * Incluye la dirección principal (campo `direccion` del modelo Cliente) y
 * todas las direcciones adicionales de la tabla `direccions`.
 */

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;

class ClienteDireccionesController extends Controller
{
    /**
     * GET /api/clientes/{id}/direcciones
     * Devuelve JSON con todas las direcciones del cliente.
     */
    public function __invoke(Cliente $cliente): JsonResponse
    {
        $direcciones = [];

        // 1) Dirección principal (campo directo en la tabla clientes)
        if (!empty($cliente->direccion)) {
            $direcciones[] = [
                'id'    => 'principal',          // valor especial
                'label' => '📍 Principal: ' . $cliente->direccion,
                'texto' => $cliente->direccion,
            ];
        }

        // 2) Direcciones adicionales (tabla direccions)
        $cliente->load('direcciones.distrito.provincia.departamento');

        foreach ($cliente->direcciones as $dir) {
            $ubigeo = '';
            if ($dir->distrito) {
                $ubigeo = ' — ' . $dir->distrito->nombre;
                if ($dir->distrito->provincia) {
                    $ubigeo .= ', ' . $dir->distrito->provincia->nombre;
                    if ($dir->distrito->provincia->departamento) {
                        $ubigeo .= ' (' . $dir->distrito->provincia->departamento->nombre . ')';

                    }
                }
            }

            $direcciones[] = [
                'id'    => $dir->id,
                'label' => '🏢 Agencia: ' . $dir->direccion . $ubigeo,
                'texto' => $dir->direccion . $ubigeo,
            ];
        }

        return response()->json($direcciones);
    }
}
