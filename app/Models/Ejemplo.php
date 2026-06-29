<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejemplo extends Model
{
    protected $table = 'ejemplos';
    protected $fillable = ['contenido_id', 'titulo', 'explicacion', 'solucion'];

    public function contenido(): BelongsTo
    {
        return $this->belongsTo(Contenido::class, 'contenido_id');
    }
}
