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
        'distrito_id',
    ];

    /**
     * Una dirección pertenece a un cliente
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Una dirección pertenece a un distrito (ubigeo)
     */
    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class);
    }

    /**
     * Acceso directo a la provincia a través del distrito
     */
    public function getProvinciaAttribute()
    {
        return $this->distrito?->provincia;
    }

    /**
     * Acceso directo al departamento a través del distrito → provincia
     */
    public function getDepartamentoAttribute()
    {
        return $this->distrito?->provincia?->departamento;
    }

    /**
     * Devuelve el ubigeo completo como string legible
     */
    public function getUbigeoCompletoAttribute(): string
    {
        if (!$this->distrito) return '';

        return implode(' / ', array_filter([
            $this->distrito->provincia->departamento->nombre ?? null,
            $this->distrito->provincia->nombre ?? null,
            $this->distrito->nombre ?? null,
        ]));
    }
}
