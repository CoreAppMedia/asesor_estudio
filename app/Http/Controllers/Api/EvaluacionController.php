<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index()
    {
        $evaluaciones = Evaluacion::with('unidad.semestre.materia')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($evaluaciones);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'unidad_id' => 'required|exists:cat_unidades,id',
            'total_preguntas' => 'required|integer|min:1',
            'tiempo_limite_minutos' => 'required|integer|min:1',
        ]);

        $evaluacion = Evaluacion::create($validated);
        return response()->json($evaluacion->load('unidad.semestre.materia'), 201);
    }

    public function show(Evaluacion $evaluacion)
    {
        return response()->json($evaluacion->load('unidad.semestre.materia'));
    }

    public function update(Request $request, Evaluacion $evaluacion)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'unidad_id' => 'required|exists:cat_unidades,id',
            'total_preguntas' => 'required|integer|min:1',
            'tiempo_limite_minutos' => 'required|integer|min:1',
        ]);

        $evaluacion->update($validated);
        return response()->json($evaluacion->load('unidad.semestre.materia'));
    }

    public function destroy(Evaluacion $evaluacion)
    {
        $evaluacion->delete();
        return response()->json(null, 204);
    }
}
