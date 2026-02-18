<?php

/**
 * MODELO: Categoria.php
 * Ubicación: app/Models/Categoria.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relación uno a muchos con Cliente
     */
    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }
}
