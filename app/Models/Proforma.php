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
        'codigo', 'cliente_id', 'direccion_id', 'contacto_id', 'user_id',
        'transaccion_id', 'temperatura_id', 'estado_id',
        'nota', 'orden', 'fecha_creacion', 'fecha_fin',
        'moneda', 'sub_total', 'monto_igv', 'total',
        'fecha_fin_update_count',
    ];

    protected $casts = [
        'fecha_creacion'         => 'date',
        'fecha_fin'              => 'date',
        'fecha_fin_update_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($proforma) {
            if (!$proforma->codigo) {
                $proforma->codigo = (string) Str::uuid();
            }
        });
    }

    // ── Helpers de moneda ────────────────────────────────────────────────────

    /** Devuelve el símbolo de la moneda: '$' o 'S/.' */
    public function simboloMoneda(): string
    {
        return $this->moneda === 'Dolares' ? '$' : 'S/.';
    }

    /** True si la proforma está en Dólares */
    public function esDolares(): bool
    {
        return $this->moneda === 'Dolares';
    }

    // ── Relaciones ───────────────────────────────────────────────────────────

    public function cliente(): BelongsTo     { return $this->belongsTo(Cliente::class); }
    public function direccion(): BelongsTo   { return $this->belongsTo(Direccion::class); }
    public function contacto(): BelongsTo    { return $this->belongsTo(Contacto::class); }
    public function user(): BelongsTo        { return $this->belongsTo(User::class); }
    public function transaccion(): BelongsTo { return $this->belongsTo(Transaccion::class); }
    public function temperatura(): BelongsTo { return $this->belongsTo(Temperatura::class); }
    public function estado(): BelongsTo      { return $this->belongsTo(Estado::class); }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'producto_proforma')
            ->withPivot(['cantidad', 'precio_unitario', 'descuento_cliente'])
            ->withTimestamps();
    }

    public function virtuals(): BelongsToMany
    {
        return $this->belongsToMany(Virtual::class, 'proforma_virtual')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }
}
