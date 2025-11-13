<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'mensaje',
        'fecha_envio',
        'leida',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

