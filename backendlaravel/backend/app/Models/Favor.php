<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favor extends Model
{
    use HasFactory;

    protected $table = 'favores';

    protected $fillable = [
        'user_id',
        'descripcion',
        'recompensa',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
