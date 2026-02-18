<?php

/**
 * CONTROLADOR: ClienteController.php
 * Ubicación: app/Http/Controllers/ClienteController.php
 */

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Direccion;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource with search and filter
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        // Búsqueda general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ruc', 'like', "%{$search}%")
                  ->orWhere('razon', 'like', "%{$search}%")
                  ->orWhere('direccion', 'like', "%{$search}%")
                  ->orWhere('telefono1', 'like', "%{$search}%")
                  ->orWhere('telefono2', 'like', "%{$search}%");
            });
        }

        // Filtros específicos
        if ($request->filled('ruc')) {
            $query->where('ruc', 'like', "%{$request->ruc}%");
        }

        if ($request->filled('razon')) {
            $query->where('razon', 'like', "%{$request->razon}%");
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('credito_aprobado')) {
            $query->whereHas('credito', function($q) use ($request) {
                $q->where('aprobacion', $request->credito_aprobado);
            });
        }

        $clientes = $query->with(['credito', 'categoria', 'contactos', 'direcciones'])->paginate(15);
        $categorias = Categoria::all();
        $creditos = Credito::all();

        return view('clientes.index', compact('clientes', 'categorias', 'creditos'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $categorias = Categoria::all();
        $creditos = Credito::all();
        $contactos = Contacto::all();

        return view('clientes.create', compact('categorias', 'creditos', 'contactos'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        // Validar datos del cliente
        $validated = $request->validate([
            'ruc' => 'required|string|size:11|unique:clientes,ruc',
            'razon' => 'required|string|max:250',
            'direccion' => 'required|string|max:200',
            'telefono1' => 'required|string|max:15',
            'telefono2' => 'nullable|string|max:15',
            'credito_id' => 'nullable|exists:creditos,id',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        // Obtener contactos e direcciones
        $contactosIds = $request->input('contactos') ? explode(',', $request->input('contactos')) : [];
        $direcciones = $request->input('direcciones', []);

        // Crear el cliente
        $cliente = Cliente::create($validated);

        // Asignar contactos (tabla intermedia cliente_contacto)
        if (!empty($contactosIds)) {
            $cliente->contactos()->sync(array_filter($contactosIds));
        }

        // Crear direcciones adicionales
        if (!empty($direcciones)) {
            foreach ($direcciones as $direccion) {
                if (!empty(trim($direccion))) {
                    Direccion::create([
                        'direccion' => $direccion,
                        'cliente_id' => $cliente->id
                    ]);
                }
            }
        }

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    /**
     * Display the specified resource
     */
    public function show(Cliente $cliente)
    {
        $cliente->load(['credito', 'categoria', 'contactos', 'direcciones', 'proformas']);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(Cliente $cliente)
    {
        $categorias = Categoria::all();
        $creditos = Credito::all();
        $contactos = Contacto::all();
        $cliente->load('contactos', 'direcciones');

        return view('clientes.edit', compact('cliente', 'categorias', 'creditos', 'contactos'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, Cliente $cliente)
    {
        // Validar datos del cliente
        $validated = $request->validate([
            'ruc' => 'required|string|size:11|unique:clientes,ruc,' . $cliente->id,
            'razon' => 'required|string|max:250',
            'direccion' => 'required|string|max:200',
            'telefono1' => 'required|string|max:15',
            'telefono2' => 'nullable|string|max:15',
            'credito_id' => 'nullable|exists:creditos,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'direcciones' => 'nullable|array',
            'direcciones.*' => 'nullable|string|max:250',
        ]);

        // Obtener contactos y direcciones
        $contactosIds = $request->input('contactos') ? explode(',', $request->input('contactos')) : [];
        $direcciones = $request->input('direcciones', []);

        // Actualizar el cliente
        $cliente->update($validated);

        // Sincronizar contactos
        if (!empty($contactosIds)) {
            $cliente->contactos()->sync(array_filter($contactosIds));
        } else {
            $cliente->contactos()->detach();
        }

        // Manejar direcciones adicionales
        $cliente->direcciones()->delete();
        if (!empty($direcciones)) {
            foreach ($direcciones as $direccion) {
                if (!empty(trim($direccion))) {
                    Direccion::create([
                        'direccion' => trim($direccion),
                        'cliente_id' => $cliente->id
                    ]);
                }
            }
        }

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }


    /**
     * Remove the specified resource from storage
     */
    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->delete();
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se pudo eliminar el cliente');
        }
    }

    /**
     * Verificar si un RUC ya existe en la BD y devolver datos del cliente
     */
    public function verificarRuc($ruc)
    {
        $cliente = Cliente::where('ruc', $ruc)->first();

        if ($cliente) {
            return response()->json([
                'existe' => true,
                'cliente' => [
                    'id' => $cliente->id,
                    'ruc' => $cliente->ruc,
                    'razon' => $cliente->razon,
                    'direccion' => $cliente->direccion,
                    'telefono1' => $cliente->telefono1,
                    'telefono2' => $cliente->telefono2,
                ],
                'url_edit' => route('clientes.edit', $cliente->id)
            ]);
        }

        return response()->json([
            'existe' => false
        ]);
    }
}
