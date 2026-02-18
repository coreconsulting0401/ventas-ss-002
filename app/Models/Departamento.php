<?php

/**
 * MODELO: Departamento.php
 * Ubicación: app/Models/Departamento.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Departamento extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    /**
     * Un departamento tiene muchas provincias
     */
    public function provincias(): HasMany
    {
        return $this->hasMany(Provincia::class);
    }
}
