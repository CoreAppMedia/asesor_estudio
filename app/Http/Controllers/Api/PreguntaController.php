<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'unidad_id' => 'required|exists:cat_unidades,id',
        ]);

        $preguntas = Pregunta::where('unidad_id', $request->unidad_id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($preguntas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unidad_id' => 'required|exists:cat_unidades,id',
            'texto_pregunta' => 'required|string',
            'tipo_pregunta' => 'required|string|in:opcion_multiple,respuesta_abierta',
            'opciones_json' => 'nullable|array',
            'respuesta_correcta' => 'required|string',
        ]);

        $pregunta = Pregunta::create($validated);
        return response()->json($pregunta, 201);
    }

    public function show(Pregunta $pregunta)
    {
        return response()->json($pregunta->load('unidad'));
    }

    public function update(Request $request, Pregunta $pregunta)
    {
        $validated = $request->validate([
            'unidad_id' => 'required|exists:cat_unidades,id',
            'texto_pregunta' => 'required|string',
            'tipo_pregunta' => 'required|string|in:opcion_multiple,respuesta_abierta',
            'opciones_json' => 'nullable|array',
            'respuesta_correcta' => 'required|string',
        ]);

        $pregunta->update($validated);
        return response()->json($pregunta);
    }

    public function destroy(Pregunta $pregunta)
    {
        $pregunta->delete();
        return response()->json(null, 204);
    }
}
