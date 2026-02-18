<?php

namespace App\Http\Controllers;

use App\Models\Temperatura;
use App\Http\Requests\TemperaturaRequest;
use Illuminate\Http\Request;

class TemperaturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Temperatura::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $temperaturas = $query->withCount('proformas')->paginate(15);
        return view('temperaturas.index', compact('temperaturas'));
    }

    public function create()
    {
        return view('temperaturas.create');
    }

    public function store(TemperaturaRequest $request)
    {
        Temperatura::create($request->validated());
        return redirect()->route('temperaturas.index')->with('success', 'Temperatura creada exitosamente');
    }

    public function show(Temperatura $temperatura)
    {
        $temperatura->load('proformas');
        return view('temperaturas.show', compact('temperatura'));
    }

    public function edit(Temperatura $temperatura)
    {
        return view('temperaturas.edit', compact('temperatura'));
    }

    public function update(TemperaturaRequest $request, Temperatura $temperatura)
    {
        $temperatura->update($request->validated());
        return redirect()->route('temperaturas.index')->with('success', 'Temperatura actualizada exitosamente');
    }

    public function destroy(Temperatura $temperatura)
    {
        try {
            $temperatura->delete();
            return redirect()->route('temperaturas.index')->with('success', 'Temperatura eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('temperaturas.index')->with('error', 'No se pudo eliminar la temperatura');
        }
    }
}
