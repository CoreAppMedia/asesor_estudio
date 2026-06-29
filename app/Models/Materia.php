<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $table = 'cat_materias';
    protected $fillable = ['nombre', 'descripcion'];

    public function semestres(): HasMany
    {
        return $this->hasMany(Semestre::class, 'materia_id');
    }
}
