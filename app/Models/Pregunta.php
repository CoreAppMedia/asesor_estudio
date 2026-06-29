<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pregunta extends Model
{
    protected $table = 'preguntas';
    protected $fillable = ['unidad_id', 'texto_pregunta', 'tipo_pregunta', 'opciones_json', 'respuesta_correcta'];
    protected $casts = [
        'opciones_json' => 'array',
    ];

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function resultados(): HasMany
    {
        return $this->hasMany(Resultado::class, 'pregunta_id');
    }
}
