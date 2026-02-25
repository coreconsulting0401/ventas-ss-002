<?php

/**
 * MODELO: Cambio.php
 * Ubicación: app/Models/Cambio.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cambio extends Model
{
    use HasFactory;

    protected $table = 'cambios';

    protected $fillable = [
        // Datos API (los llena el comando, NO el usuario)
        'origen',
        'compra',
        'venta',
        'moneda',
        'fecha',
        // Calculado
        'venta_mas',
        // Configurable por el usuario
        'incremento',
        // Control de proceso
        'estado',
        'intentos',
        'error_mensaje',
    ];

    protected $casts = [
        'fecha'      => 'date',
        'compra'     => 'decimal:4',
        'venta'      => 'decimal:4',
        'venta_mas'  => 'decimal:4',
        'incremento' => 'decimal:4',
    ];

    /**
     * Recalcula venta_mas y guarda, llamado cada vez que cambia
     * el incremento o se reciben datos de la API.
     */
    public function recalcularVentaMas(): void
    {
        if ($this->venta !== null) {
            $this->venta_mas = round((float) $this->venta + (float) $this->incremento, 4);
            $this->saveQuietly();
        }
    }

    /**
     * Devuelve el registro del día de hoy o null.
     */
    public static function hoy(): ?self
    {
        return static::where('fecha', now()->toDateString())->first();
    }

    /**
     * Badge de color según estado.
     */
    public function estadoBadgeClass(): string
    {
        return match ($this->estado) {
            'ok'        => 'success',
            'error'     => 'danger',
            'pendiente' => 'warning',
            default     => 'secondary',
        };
    }
}
