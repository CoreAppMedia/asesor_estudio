<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';
    protected $fillable = ['nombre', 'unidad_id', 'total_preguntas', 'tiempo_limite_minutos'];

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(Sesion::class, 'evaluacion_id');
    }
}
