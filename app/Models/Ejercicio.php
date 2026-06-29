<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejercicio extends Model
{
    protected $table = 'ejercicios';
    protected $fillable = ['contenido_id', 'instruccion', 'respuesta_sugerida'];

    public function contenido(): BelongsTo
    {
        return $this->belongsTo(Contenido::class, 'contenido_id');
    }
}
