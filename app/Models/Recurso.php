<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recurso extends Model
{
    protected $table = 'recursos';
    protected $fillable = ['contenido_id', 'tipo', 'url', 'descripcion'];

    public function contenido(): BelongsTo
    {
        return $this->belongsTo(Contenido::class, 'contenido_id');
    }
}
