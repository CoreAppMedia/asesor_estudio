<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contenido extends Model
{
    protected $table = 'contenidos';
    protected $fillable = ['subtema_id', 'tema_id', 'tipo', 'json_path', 'metadata'];
    protected $casts = [
        'metadata' => 'array',
    ];

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'tema_id');
    }

    public function subtema(): BelongsTo
    {
        return $this->belongsTo(Subtema::class, 'subtema_id');
    }

    public function recursos(): HasMany
    {
        return $this->hasMany(Recurso::class, 'contenido_id');
    }

    public function ejemplos(): HasMany
    {
        return $this->hasMany(Ejemplo::class, 'contenido_id');
    }

    public function ejercicios(): HasMany
    {
        return $this->hasMany(Ejercicio::class, 'contenido_id');
    }
}
