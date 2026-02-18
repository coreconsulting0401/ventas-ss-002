<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Igv extends Model
{
    protected $fillable = ['porcentaje', 'activo'];

    // Método estático para obtener el valor actual fácilmente
    public static function actual()
    {
        return self::where('activo', true)->first()->porcentaje ?? 18;
    }
}