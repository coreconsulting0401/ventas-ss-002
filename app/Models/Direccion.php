<?php

/**
 * MODELO: Direccion.php
 * Ubicación: app/Models/Direccion.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Direccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'direccion',
        'cliente_id',
    ];

    /**
     * Relación muchos a uno con Cliente
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
