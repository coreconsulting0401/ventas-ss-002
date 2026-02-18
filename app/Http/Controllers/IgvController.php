<?php

namespace App\Http\Controllers;

use App\Models\Igv;
use Illuminate\Http\Request;

class IgvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Igv $igv)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Igv $igv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateIgv(Request $request)
    {
        $request->validate(['porcentaje' => 'required|numeric|min:0']);

        // Opción A: Actualizar el único registro existente
        $igv = Igv::firstOrCreate(['id' => 1]);
        $igv->update(['porcentaje' => $request->porcentaje]);

        return back()->with('success', 'IGV actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Igv $igv)
    {
        //
    }
}
