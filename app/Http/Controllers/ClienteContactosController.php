<?php

/**
 * CONTROLADOR: ClienteContactosController.php
 * Ubicación: app/Http/Controllers/ClienteContactosController.php
 *
 * Endpoint: GET /api/clientes/{cliente}/contactos
 * Devuelve los contactos asociados a un cliente en JSON
 * para carga dinámica en los formularios de Proforma.
 */

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;

class ClienteContactosController extends Controller
{
    public function __invoke(Cliente $cliente): JsonResponse
    {
        $contactos = $cliente->contactos()
            ->orderBy('apellido_paterno')
            ->orderBy('nombre')
            ->get(['contactos.id', 'nombre', 'apellido_paterno', 'apellido_materno', 'cargo', 'telefono', 'email'])
            ->map(fn($c) => [
                'id'             => $c->id,
                'label'          => "{$c->apellido_paterno} {$c->apellido_materno}, {$c->nombre}" . ($c->cargo ? " — {$c->cargo}" : ''),
                'nombre_completo'=> "{$c->nombre} {$c->apellido_paterno} {$c->apellido_materno}",
                'cargo'          => $c->cargo,
                'telefono'       => $c->telefono,
                'email'          => $c->email,
            ]);

        return response()->json($contactos);
    }
}
