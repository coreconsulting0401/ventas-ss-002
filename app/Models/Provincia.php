<?php

/**
 * MODELO: Provincia.php
 * Ubicación: app/Models/Provincia.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'departamento_id'];

    /**
     * Una provincia pertenece a un departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Una provincia tiene muchos distritos
     */
    public function distritos(): HasMany
    {
        return $this->hasMany(Distrito::class);
    }
}
