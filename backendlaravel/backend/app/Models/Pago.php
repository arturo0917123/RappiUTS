<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'metodo',
        'monto',
        'fecha_pago',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
