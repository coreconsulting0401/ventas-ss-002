<?php

/**
 * CONTROLADOR: CreditoController.php
 * Ubicación: app/Http/Controllers/CreditoController.php
 */

namespace App\Http\Controllers;

use App\Models\Credito;
use App\Http\Requests\CreditoRequest;
use Illuminate\Http\Request;

class CreditoController extends Controller
{
    public function index(Request $request)
    {
        $query = Credito::query();

        if ($request->filled('aprobacion')) {
            $query->where('aprobacion', $request->aprobacion);
        }

        $creditos = $query->with('clientes')->paginate(15);
        return view('creditos.index', compact('creditos'));
    }

    public function create()
    {
        return view('creditos.create');
    }

    public function store(CreditoRequest $request)
    {
        Credito::create($request->validated());
        return redirect()->route('creditos.index')->with('success', 'Crédito creado exitosamente');
    }

    public function show(Credito $credito)
    {
        $credito->load('clientes');
        return view('creditos.show', compact('credito'));
    }

    public function edit(Credito $credito)
    {
        return view('creditos.edit', compact('credito'));
    }

    public function update(CreditoRequest $request, Credito $credito)
    {
        $credito->update($request->validated());
        return redirect()->route('creditos.index')->with('success', 'Crédito actualizado exitosamente');
    }

    public function destroy(Credito $credito)
    {
        try {
            $credito->delete();
            return redirect()->route('creditos.index')->with('success', 'Crédito eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('creditos.index')->with('error', 'No se pudo eliminar el crédito');
        }
    }
}
