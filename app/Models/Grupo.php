<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $fillable = ['generacion_id', 'nombre'];

    public function generacion(): BelongsTo
    {
        return $this->belongsTo(Generacion::class, 'generacion_id');
    }

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'grupo_id');
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(Sesion::class, 'grupo_id');
    }
}
