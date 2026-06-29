<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unidad extends Model
{
    protected $table = 'cat_unidades';
    protected $fillable = ['semestre_id', 'numero', 'nombre', 'descripcion'];

    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }

    public function temas(): HasMany
    {
        return $this->hasMany(Tema::class, 'unidad_id');
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class, 'unidad_id');
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(Evaluacion::class, 'unidad_id');
    }
}
