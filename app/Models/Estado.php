<?php

/**
 * MODELO: Estado.php
 * Ubicación: app/Models/Estado.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
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
