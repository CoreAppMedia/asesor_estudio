<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $table = 'alumnos';
    protected $fillable = ['grupo_id', 'numero_lista', 'nombre', 'apellido_paterno', 'apellido_materno', 'edad', 'numero_celular'];

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function intentos(): HasMany
    {
        return $this->hasMany(Intento::class, 'alumno_id');
    }
}
