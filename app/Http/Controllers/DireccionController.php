<?php

/**
 * CONTROLADOR: DireccionController.php
 * Ubicación: app/Http/Controllers/DireccionController.php
 */

namespace App\Http\Controllers;

use App\Models\Direccion;
use App\Models\Cliente;
use App\Http\Requests\DireccionRequest;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    public function index(Request $request)
    {
        $query = Direccion::query();

        if ($request->filled('search')) {
            $query->where('direccion', 'like', "%{$request->search}%");
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        $direcciones = $query->with('cliente')->paginate(15);
        $clientes = Cliente::all();

        return view('direcciones.index', compact('direcciones', 'clientes'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('direcciones.create', compact('clientes'));
    }

    public function store(DireccionRequest $request)
    {
        Direccion::create($request->validated());
        return redirect()->route('direcciones.index')->with('success', 'Dirección creada exitosamente');
    }

    public function show(Direccion $direccion)
    {
        $direccion->load('cliente');
        return view('direcciones.show', compact('direccion'));
    }

    public function edit(Direccion $direccion)
    {
        $clientes = Cliente::all();
        return view('direcciones.edit', compact('direccion', 'clientes'));
    }

    public function update(DireccionRequest $request, Direccion $direccion)
    {
        $direccion->update($request->validated());
        return redirect()->route('direcciones.index')->with('success', 'Dirección actualizada exitosamente');
    }

    public function destroy(Direccion $direccion)
    {
        try {
            $direccion->delete();
            return redirect()->route('direcciones.index')->with('success', 'Dirección eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('direcciones.index')->with('error', 'No se pudo eliminar la dirección');
        }
    }
}
