<?php

/**
 * CONTROLADOR: UbigeoController.php
 * Ubicación: app/Http/Controllers/UbigeoController.php
 *
 * Devuelve provincias y distritos como JSON para los selects encadenados
 * en las vistas de creación/edición de clientes.
 */

namespace App\Http\Controllers;

use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\JsonResponse;

class UbigeoController extends Controller
{
    /**
     * Devuelve las provincias de un departamento
     * GET /ubigeo/provincias/{departamento_id}
     */
    public function provincias(int $departamentoId): JsonResponse
    {
        $provincias = Provincia::where('departamento_id', $departamentoId)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return response()->json($provincias);
    }

    /**
     * Devuelve los distritos de una provincia
     * GET /ubigeo/distritos/{provincia_id}
     */
    public function distritos(int $provinciaId): JsonResponse
    {
        $distritos = Distrito::where('provincia_id', $provinciaId)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return response()->json($distritos);
    }
}
