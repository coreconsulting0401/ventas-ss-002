<?php

/**
 * CONTROLADOR: ProductoController.php
 * Ubicación: app/Http/Controllers/ProductoController.php
 */

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Descuento;
use App\Http\Requests\ProductoRequest;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing with search and filters
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        // Búsqueda general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo_e', 'like', "%{$search}%")
                  ->orWhere('codigo_p', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('ubicacion', 'like', "%{$search}%");
            });
        }

        // Filtros específicos
        if ($request->filled('codigo_e')) {
            $query->where('codigo_e', 'like', "%{$request->codigo_e}%");
        }

        if ($request->filled('codigo_p')) {
            $query->where('codigo_p', 'like', "%{$request->codigo_p}%");
        }

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }

        if ($request->filled('marca')) {
            $query->where('marca', 'like', "%{$request->marca}%");
        }

        if ($request->filled('ubicacion')) {
            $query->where('ubicacion', 'like', "%{$request->ubicacion}%");
        }

        if ($request->filled('descuento_id')) {
            $query->where('descuento_id', $request->descuento_id);
        }

        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        }

        if ($request->filled('precio_min')) {
            $query->where('precio_lista', '>=', $request->precio_min);
        }

        if ($request->filled('precio_max')) {
            $query->where('precio_lista', '<=', $request->precio_max);
        }

        $productos = $query->with('descuento')->paginate(15);
        $descuentos = Descuento::all();

        return view('productos.index', compact('productos', 'descuentos'));
    }

    public function create()
    {
        $descuentos = Descuento::all();
        return view('productos.create', compact('descuentos'));
    }

    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());
        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        $producto->load(['descuento', 'proformas']);
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $descuentos = Descuento::all();
        return view('productos.edit', compact('producto', 'descuentos'));
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());
        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        try {
            $producto->delete();
            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'No se pudo eliminar el producto');
        }
    }
}
