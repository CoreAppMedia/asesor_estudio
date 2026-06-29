<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sesion extends Model
{
    protected $table = 'sesiones';
    protected $fillable = ['grupo_id', 'evaluacion_id', 'codigo_acceso', 'activa', 'fecha_cierre'];
    protected $casts = [
        'activa' => 'boolean',
        'fecha_cierre' => 'datetime',
    ];

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(Evaluacion::class, 'evaluacion_id');
    }

    public function intentos(): HasMany
    {
        return $this->hasMany(Intento::class, 'sesion_id');
    }
}
