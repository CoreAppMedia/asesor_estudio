<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semestre extends Model
{
    protected $table = 'cat_semestres';
    protected $fillable = ['materia_id', 'numero', 'descripcion'];

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidad::class, 'semestre_id');
    }
}
