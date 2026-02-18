<?php

/**
 * MODELO: Virtual.php
 * Ubicación: app/Models/Virtual.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Virtual extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio_compra',
        'precio_venta',
        'marca',
        'stock',
    ];

    protected $casts = [
        'precio_venta' => 'decimal:3',
    ];

    /**
     * Relación muchos a muchos con Proforma
     */
    public function proformas(): BelongsToMany
    {
        return $this->belongsToMany(Proforma::class, 'proforma_virtual')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }

    /**
     * Relación muchos a muchos con Proveedor
     */
    public function proveedores(): BelongsToMany
    {
        return $this->belongsToMany(Proveedor::class, 'proveedor_virtual')
            ->withTimestamps();
    }
}
