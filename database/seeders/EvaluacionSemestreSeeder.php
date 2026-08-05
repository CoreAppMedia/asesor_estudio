<?php

namespace Database\Seeders;

use App\Models\Semestre;
use App\Models\Evaluacion;
use Illuminate\Database\Seeder;

class EvaluacionSemestreSeeder extends Seeder
{
    public function run(): void
    {
        $semestres = Semestre::all();

        foreach ($semestres as $semestre) {
            $nombreSemestre = $semestre->descripcion ?: "Matemáticas " . $semestre->numero;

            // 1. Repaso General
            $nombreRepaso = "Repaso General - " . $nombreSemestre;
            if (!Evaluacion::where('nombre', $nombreRepaso)->exists()) {
                Evaluacion::create([
                    'nombre' => $nombreRepaso,
                    'semestre_id' => $semestre->id,
                    'unidad_id' => null,
                    'total_preguntas' => 20,
                    'tiempo_limite_minutos' => 60,
                ]);
            }

            // 2. Examen Final
            $nombreExamenFinal = "Examen Final - " . $nombreSemestre;
            if (!Evaluacion::where('nombre', $nombreExamenFinal)->exists()) {
                Evaluacion::create([
                    'nombre' => $nombreExamenFinal,
                    'semestre_id' => $semestre->id,
                    'unidad_id' => null,
                    'total_preguntas' => 20,
                    'tiempo_limite_minutos' => 60,
                ]);
            }
        }
    }
}
