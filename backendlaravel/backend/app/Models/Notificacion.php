<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'destinatario_id',
        'mensaje',
        'fecha_envio',
        'leida',
        'estado',
        'respuesta'
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'leida' => 'boolean'
    ];

    // Relación con el usuario que envía
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el usuario que recibe
    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id');
    }
}
