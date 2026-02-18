<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Http\Requests\EstadoRequest;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Estado::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $estados = $query->withCount('proformas')->paginate(15);
        return view('estados.index', compact('estados'));
    }

    public function create()
    {
        return view('estados.create');
    }

    public function store(EstadoRequest $request)
    {
        Estado::create($request->validated());
        return redirect()->route('estados.index')->with('success', 'Estado creado exitosamente');
    }

    public function show(Estado $estado)
    {
        $estado->load('proformas');
        return view('estados.show', compact('estado'));
    }

    public function edit(Estado $estado)
    {
        return view('estados.edit', compact('estado'));
    }

    public function update(EstadoRequest $request, Estado $estado)
    {
        $estado->update($request->validated());
        return redirect()->route('estados.index')->with('success', 'Estado actualizado exitosamente');
    }

    public function destroy(Estado $estado)
    {
        try {
            $estado->delete();
            return redirect()->route('estados.index')->with('success', 'Estado eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('estados.index')->with('error', 'No se pudo eliminar el estado');
        }
    }
}
