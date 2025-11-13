<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'emprendimiento_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
    ];

    public function emprendimiento()
    {
        return $this->belongsTo(Emprendimiento::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
