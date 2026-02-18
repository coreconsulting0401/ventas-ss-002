<?php

namespace App\Http\Controllers;

use App\Models\Virtual;
use App\Models\Proveedor;
use App\Http\Requests\VirtualRequest;
use Illuminate\Http\Request;

class VirtualController extends Controller
{
    public function index(Request $request)
    {
        $query = Virtual::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('precio_compra', 'like', "%{$search}%");
            });
        }

        if ($request->filled('marca')) {
            $query->where('marca', 'like', "%{$request->marca}%");
        }

        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }

        $virtuals = $query->with('proveedores')->paginate(15);
        return view('virtuals.index', compact('virtuals'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        return view('virtuals.create', compact('proveedores'));
    }

    public function store(VirtualRequest $request)
    {
        $data = $request->validated();
        $proveedoresIds = $data['proveedores'] ?? [];
        unset($data['proveedores']);

        $virtual = Virtual::create($data);

        if (!empty($proveedoresIds)) {
            $virtual->proveedores()->sync($proveedoresIds);
        }

        return redirect()->route('virtuals.index')->with('success', 'Virtual creado exitosamente');
    }

    public function show(Virtual $virtual)
    {
        $virtual->load(['proveedores', 'proformas']);
        return view('virtuals.show', compact('virtual'));
    }

    public function edit(Virtual $virtual)
    {
        $proveedores = Proveedor::all();
        $virtual->load('proveedores');
        return view('virtuals.edit', compact('virtual', 'proveedores'));
    }

    public function update(VirtualRequest $request, Virtual $virtual)
    {
        $data = $request->validated();
        $proveedoresIds = $data['proveedores'] ?? [];
        unset($data['proveedores']);

        $virtual->update($data);
        $virtual->proveedores()->sync($proveedoresIds);

        return redirect()->route('virtuals.index')->with('success', 'Virtual actualizado exitosamente');
    }

    public function destroy(Virtual $virtual)
    {
        try {
            $virtual->delete();
            return redirect()->route('virtuals.index')->with('success', 'Virtual eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('virtuals.index')->with('error', 'No se pudo eliminar el virtual');
        }
    }
}
