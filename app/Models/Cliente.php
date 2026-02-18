<?php

/**
 * MODELO: Cliente.php
 * Ubicación: app/Models/Cliente.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruc',
        'razon',
        'direccion',
        'telefono1',
        'telefono2',
        'credito_id',
        'categoria_id',
    ];

    /**
     * Relación muchos a uno con Credito
     */
    public function credito(): BelongsTo
    {
        return $this->belongsTo(Credito::class);
    }

    /**
     * Relación muchos a uno con Categoria
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relación uno a muchos con Direccion
     */
    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }

    /**
     * Relación muchos a muchos con Contacto
     */
    public function contactos(): BelongsToMany
    {
        return $this->belongsToMany(
            Contacto::class,           // Modelo relacionado
            'cliente_contacto',        // Tabla intermedia
            'cliente_id',              // Foreign key de este modelo
            'contacto_id'              // Foreign key del otro modelo
        );
    }

    /**
     * Relación uno a muchos con Proforma
     */
    public function proformas(): HasMany
    {
        return $this->hasMany(Proforma::class);
    }
}
