<?php

/**
 * MODELO: Temperatura.php
 * Ubicación: app/Models/Temperatura.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Temperatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relación uno a muchos con Proforma
     */
    public function proformas(): HasMany
    {
        return $this->hasMany(Proforma::class);
    }
}
