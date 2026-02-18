<?php

/**
 * MODELO: Descuento.php
 * Ubicación: app/Models/Descuento.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Descuento extends Model
{
    use HasFactory;

    protected $fillable = [
        'porcentaje',
    ];

    /**
     * Relación uno a muchos con Producto
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
