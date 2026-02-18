<?php

/**
 * MODELO: Credito.php
 * Ubicación: app/Models/Credito.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Credito extends Model
{
    use HasFactory;

    protected $fillable = [
        'aprobacion',
    ];

    protected $casts = [
        'aprobacion' => 'boolean',
    ];

    /**
     * Relación uno a muchos con Cliente
     */
    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }
}
