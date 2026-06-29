<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intento extends Model
{
    protected $table = 'intentos';
    protected $fillable = ['alumno_id', 'sesion_id', 'numero_intento', 'iniciado_at', 'finalizado_at', 'session_token'];
    protected $casts = [
        'iniciado_at' => 'datetime',
        'finalizado_at' => 'datetime',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function sesion(): BelongsTo
    {
        return $this->belongsTo(Sesion::class, 'sesion_id');
    }

    public function resultados(): HasMany
    {
        return $this->hasMany(Resultado::class, 'intento_id');
    }
}
