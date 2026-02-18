<?php

/**
 * MODELO: Proveedor.php
 * Ubicación: app/Models/Proveedor.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruc',
        'razon',
        'direccion',
    ];

    /**
     * Relación muchos a muchos con Virtual
     */
    public function virtuals(): BelongsToMany
    {
        return $this->belongsToMany(Virtual::class, 'proveedor_virtual')
            ->withTimestamps();
    }
}
