<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tema extends Model
{
    protected $table = 'cat_temas';
    protected $fillable = ['unidad_id', 'numero', 'nombre'];

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function subtemas(): HasMany
    {
        return $this->hasMany(Subtema::class, 'tema_id');
    }

    public function contenidos(): HasMany
    {
        return $this->hasMany(Contenido::class, 'tema_id');
    }
}
