<?php

/**
 * CONTROLADOR: DescuentoController.php
 * Ubicación: app/Http/Controllers/DescuentoController.php
 */

namespace App\Http\Controllers;

use App\Models\Descuento;
use App\Http\Requests\DescuentoRequest;
use Illuminate\Http\Request;

class DescuentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Descuento::query();

        if ($request->filled('porcentaje_min')) {
            $query->where('porcentaje', '>=', $request->porcentaje_min);
        }

        if ($request->filled('porcentaje_max')) {
            $query->where('porcentaje', '<=', $request->porcentaje_max);
        }

        $descuentos = $query->withCount('productos')->paginate(15);
        return view('descuentos.index', compact('descuentos'));
    }

    public function create()
    {
        return view('descuentos.create');
    }

    public function store(DescuentoRequest $request)
    {
        Descuento::create($request->validated());
        return redirect()->route('descuentos.index')->with('success', 'Descuento creado exitosamente');
    }

    public function show(Descuento $descuento)
    {
        $descuento->load('productos');
        return view('descuentos.show', compact('descuento'));
    }

    public function edit(Descuento $descuento)
    {
        return view('descuentos.edit', compact('descuento'));
    }

    public function update(DescuentoRequest $request, Descuento $descuento)
    {
        $descuento->update($request->validated());
        return redirect()->route('descuentos.index')->with('success', 'Descuento actualizado exitosamente');
    }

    public function destroy(Descuento $descuento)
    {
        try {
            $descuento->delete();
            return redirect()->route('descuentos.index')->with('success', 'Descuento eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('descuentos.index')->with('error', 'No se pudo eliminar el descuento');
        }
    }
}
