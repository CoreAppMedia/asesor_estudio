<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resultado extends Model
{
    protected $table = 'resultados';
    protected $fillable = ['intento_id', 'pregunta_id', 'respuesta_alumno', 'es_correcta', 'puntaje', 'feedback_profesor'];
    protected $casts = [
        'es_correcta' => 'boolean',
        'puntaje' => 'decimal:2',
    ];

    public function intento(): BelongsTo
    {
        return $this->belongsTo(Intento::class, 'intento_id');
    }

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }
}
