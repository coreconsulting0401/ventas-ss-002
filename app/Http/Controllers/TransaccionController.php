<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Http\Requests\TransaccionRequest;
use Illuminate\Http\Request;

class TransaccionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaccion::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $transacciones = $query->withCount('proformas')->paginate(15);
        return view('transacciones.index', compact('transacciones'));
    }

    public function create()
    {
        return view('transacciones.create');
    }

    public function store(TransaccionRequest $request)
    {
        Transaccion::create($request->validated());
        return redirect()->route('transacciones.index')->with('success', 'Transacción creada exitosamente');
    }

    public function show(Transaccion $transaccion)
    {
        $transaccion->load('proformas');
        return view('transacciones.show', compact('transaccion'));
    }

    public function edit(Transaccion $transaccion)
    {
        return view('transacciones.edit', compact('transaccion'));
    }

    public function update(TransaccionRequest $request, Transaccion $transaccion)
    {
        $transaccion->update($request->validated());
        return redirect()->route('transacciones.index')->with('success', 'Transacción actualizada exitosamente');
    }

    public function destroy(Transaccion $transaccion)
    {
        try {
            $transaccion->delete();
            return redirect()->route('transacciones.index')->with('success', 'Transacción eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('transacciones.index')->with('error', 'No se pudo eliminar la transacción');
        }
    }
}
