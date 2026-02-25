<?php
// app/Models/Empresa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Empresa extends Model
{
    protected $fillable = [
        'razon_social',
        'ruc',
        'direccion',
        'pagina_web',
        'uri_img_logo',
        'uri_img_publicidad',
        'uri_img_condiciones',
        'uri_cuentas_bancarias',
    ];

    public function emails(): HasMany
    {
        return $this->hasMany(EmailEmpresa::class);
    }

    public function telefonos(): HasMany
    {
        return $this->hasMany(TelefonoEmpresa::class);
    }

    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    public static function getInstance(): ?self
    {
        return self::first();
    }

    public static function exists(): bool
    {
        return self::count() > 0;
    }
}
