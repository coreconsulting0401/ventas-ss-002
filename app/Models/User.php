<?php

/**
 * MODELO: User.php
 * Ubicación: app/Models/User.php
 */
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'codigo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot del modelo para generar el código automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if (!$user->codigo) {
                $iniciales = strtoupper(substr($user->name, 0, 2));
                $user->codigo = $user->id . $iniciales;
                $user->save();
            }
        });
    }

    /**
     * Relación uno a muchos con Proforma
     */
    public function proformas(): HasMany
    {
        return $this->hasMany(Proforma::class);
    }
}
