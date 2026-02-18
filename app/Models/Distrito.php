<?php

/**
 * MODELO: Distrito.php
 * Ubicación: app/Models/Distrito.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distrito extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'provincia_id'];

    /**
     * Un distrito pertenece a una provincia
     */
    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * Un distrito puede tener muchas direcciones
     */
    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }
}
