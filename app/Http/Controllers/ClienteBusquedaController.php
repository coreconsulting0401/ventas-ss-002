<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteBusquedaController extends Controller
{
    /**
     * Buscar clientes por RUC o Razón Social (para Select2)
     *
     * GET /api/clientes/buscar?q=término
     */
    public function buscar(Request $request)
    {
        $termino = $request->input('q', '');

        if (strlen($termino) < 2) {
            return response()->json([
                'results' => []
            ]);
        }

        $clientes = Cliente::where(function($query) use ($termino) {
                $query->where('ruc', 'like', "%{$termino}%")
                      ->orWhere('razon', 'like', "%{$termino}%");
            })
            ->select('id', 'ruc', 'razon', 'direccion')
            ->limit(20)
            ->get();

        // Formatear para Select2
        $results = $clientes->map(function($cliente) {
            return [
                'id' => $cliente->id,
                'text' => "{$cliente->razon} - RUC: {$cliente->ruc}",
                'ruc' => $cliente->ruc,
                'razon' => $cliente->razon,
                'direccion' => $cliente->direccion
            ];
        });

        return response()->json([
            'results' => $results
        ]);
    }

    /**
     * Obtener un cliente específico por ID (para pre-cargar en edición)
     *
     * GET /api/clientes/{id}
     */
    public function obtener($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        return response()->json([
            'id' => $cliente->id,
            'text' => "{$cliente->razon} - RUC: {$cliente->ruc}",
            'ruc' => $cliente->ruc,
            'razon' => $cliente->razon,
            'direccion' => $cliente->direccion
        ]);
    }
}
