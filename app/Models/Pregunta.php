<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregunta', 'imagen', 'respuesta_correcta'
    ];

    // Relación con el modelo Respuesta
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'pregunta_id');
    }
}
