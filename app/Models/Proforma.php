<?php

/**
 * MODELO: Proforma.php
 * Ubicación: app/Models/Proforma.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Proforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'cliente_id',
        'user_id',
        'transaccion_id',
        'temperatura_id',
        'estado_id',
        'nota',
        'orden',
        'fecha_creacion',
        'fecha_fin',
        'moneda',
        'sub_total',
        'monto_igv',
        'total',
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Boot del modelo para generar UUID automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proforma) {
            if (!$proforma->codigo) {
                $proforma->codigo = (string) Str::uuid();
            }
        });
    }

    /**
     * Relación muchos a uno con Cliente
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación muchos a uno con User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación muchos a uno con Transaccion
     */
    public function transaccion(): BelongsTo
    {
        return $this->belongsTo(Transaccion::class);
    }

    /**
     * Relación muchos a uno con Temperatura
     */
    public function temperatura(): BelongsTo
    {
        return $this->belongsTo(Temperatura::class);
    }

    /**
     * Relación muchos a uno con Estado
     */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    /**
    * Relación muchos a muchos con Producto
    */
    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'producto_proforma')
            ->withPivot(['cantidad', 'precio_unitario', 'descuento_cliente'])
            ->withTimestamps();
    }

    /**
     * Relación muchos a muchos con Virtual
     */
    public function virtuals(): BelongsToMany
    {
        return $this->belongsToMany(Virtual::class, 'proforma_virtual')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }
}
