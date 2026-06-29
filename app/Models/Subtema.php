<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subtema extends Model
{
    protected $table = 'cat_subtemas';
    protected $fillable = ['tema_id', 'numero', 'nombre'];

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'tema_id');
    }

    public function contenidos(): HasMany
    {
        return $this->hasMany(Contenido::class, 'subtema_id');
    }
}
