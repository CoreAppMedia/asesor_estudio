<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generacion extends Model
{
    protected $table = 'generaciones';
    protected $fillable = ['anio_inicio', 'anio_fin'];

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class, 'generacion_id');
    }
}
