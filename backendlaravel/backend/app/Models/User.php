<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'bio',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relaciones
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function favores()
    {
        return $this->hasMany(Favor::class);
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function emprendimientos()
    {
        return $this->hasMany(Emprendimiento::class);
    }
}
