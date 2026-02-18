<?php
/**
 * MODELO: Contacto.php
 * Ubicación: app/Models/Contacto.php
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contacto extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'email',
        'cargo',
    ];

    /**
     * Relación muchos a muchos con Cliente
     */
    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(Cliente::class, 'cliente_contacto');
    }
}
