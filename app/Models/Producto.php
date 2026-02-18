<?php

/**
 * MODELO: Producto.php
 * Ubicación: app/Models/Producto.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_e',
        'codigo_p',
        'nombre',
        'marca',
        'ubicacion',
        'precio_lista',
        'stock',
        'descuento_id',
    ];

    protected $casts = [
        'precio_lista' => 'decimal:3',
    ];

    /**
     * Relación muchos a uno con Descuento
     */
    public function descuento(): BelongsTo
    {
        return $this->belongsTo(Descuento::class);
    }

    /**
     * Relación muchos a muchos con Proforma
     */
    public function proformas(): BelongsToMany
    {
        return $this->belongsToMany(Proforma::class, 'producto_proforma')
            ->withPivot(['cantidad', 'precio_unitario', 'descuento_cliente'])
            ->withTimestamps();
    }
}
