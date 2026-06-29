<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pregunta;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CuestionarioController extends Controller
{
    private function getCacheKey(Request $request, Unidad $unidad): string
    {
        $ip = $request->ip();
        $clientUuid = $request->header('X-Client-UUID', 'anonymous');
        $date = date('Y-m-d');
        return "attempts_cuestionario_{$ip}_{$clientUuid}_{$unidad->id}_{$date}";
    }

    public function getCuestionario(Unidad $unidad, Request $request)
    {
        $cacheKey = $this->getCacheKey($request, $unidad);
        $attempts = Cache::get($cacheKey, 0);

        if ($attempts >= 3) {
            return response()->json([
                'message' => 'Has alcanzado el límite de 3 intentos diarios para esta unidad. ¡Sigue repasando la teoría y vuelve a intentarlo mañana!'
            ], 403);
        }

        // Obtener 5 preguntas de forma aleatoria
        $preguntas = Pregunta::where('unidad_id', $unidad->id)
            ->inRandomOrder()
            ->take(5)
            ->get();

        if ($preguntas->isEmpty()) {
            return response()->json([
                'message' => 'No hay preguntas disponibles en el banco para esta unidad por el momento.'
            ], 404);
        }

        // Estructura segura: excluir la columna "respuesta_correcta"
        $preguntasPayload = $preguntas->map(function ($p) {
            return [
                'id' => $p->id,
                'texto_pregunta' => $p->texto_pregunta,
                'tipo_pregunta' => $p->tipo_pregunta,
                'opciones_json' => $p->opciones_json,
            ];
        });

        return response()->json([
            'unidad_id' => $unidad->id,
            'preguntas' => $preguntasPayload,
            'attempts_count' => $attempts,
            'attempts_remaining' => 3 - $attempts
        ]);
    }

    public function evaluarCuestionario(Request $request, Unidad $unidad)
    {
        $cacheKey = $this->getCacheKey($request, $unidad);
        $attempts = Cache::get($cacheKey, 0);

        if ($attempts >= 3) {
            return response()->json([
                'message' => 'Has alcanzado el límite de 3 intentos diarios para esta unidad. No es posible enviar más respuestas hoy.'
            ], 403);
        }

        $validated = $request->validate([
            'respuestas' => 'nullable|array',
        ]);

        $respuestas = $validated['respuestas'] ?? [];

        // Incrementar contador de intentos en cache por 24 horas
        Cache::put($cacheKey, $attempts + 1, now()->addDay());

        $questionIds = array_keys($respuestas);
        $preguntas = Pregunta::whereIn('id', $questionIds)
            ->where('unidad_id', $unidad->id)
            ->get()
            ->keyBy('id');

        $score = 0;
        $total = 0;
        $results = [];

        foreach ($preguntas as $q) {
            $total++;
            $ansAlumno = $respuestas[$q->id] ?? null;
            $esCorrecta = ($ansAlumno !== null && strtolower(trim($ansAlumno)) === strtolower(trim($q->respuesta_correcta)));

            if ($esCorrecta) {
                $score++;
            }

            $results[] = [
                'pregunta_id' => $q->id,
                'texto_pregunta' => $q->texto_pregunta,
                'opciones_json' => $q->opciones_json,
                'respuesta_alumno' => $ansAlumno,
                'respuesta_correcta' => $q->respuesta_correcta,
                'es_correcta' => $esCorrecta,
                'feedback' => $esCorrecta 
                    ? '¡Excelente! Respuesta correcta.' 
                    : 'Incorrecto. La respuesta correcta era: ' . $q->respuesta_correcta
            ];
        }

        $score10 = $total > 0 ? round(($score / $total) * 10, 1) : 0;

        return response()->json([
            'unidad_id' => $unidad->id,
            'score' => $score10,
            'correct_count' => $score,
            'total_count' => $total,
            'results' => $results,
            'attempts_count' => $attempts + 1,
            'attempts_remaining' => max(0, 3 - ($attempts + 1)),
        ]);
    }
}
