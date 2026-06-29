<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Unidad;
use App\Models\Contenido;
use Illuminate\Http\Request;

class ContenidoController extends Controller
{
    public function getCursos()
    {
        // Cachear el catálogo completo de materias y unidades por 24 horas
        // Se usa ->toArray() para evitar guardar objetos Eloquent serializados en caché
        $cursos = \Illuminate\Support\Facades\Cache::remember('cursos_lista_completa', 86400, function () {
            return Materia::with([
                'semestres.unidades.temas'
            ])->get()->toArray();
        });

        return response()->json($cursos);
    }

    public function getUnidadContenido(Unidad $unidad)
    {
        // Encontrar la siguiente unidad en el plan de estudios
        $allUnits = Unidad::with('semestre')
            ->get()
            ->sortBy([
                ['semestre.numero', 'asc'],
                ['numero', 'asc']
            ])
            ->values();

        $currentIndex = $allUnits->search(function ($item) use ($unidad) {
            return $item->id === $unidad->id;
        });

        $siguienteUnidad = null;
        if ($currentIndex !== false && $currentIndex < $allUnits->count() - 1) {
            $siguienteUnidad = $allUnits[$currentIndex + 1];
        }

        $siguienteUnidadData = $siguienteUnidad ? [
            'id' => $siguienteUnidad->id,
            'numero' => $siguienteUnidad->numero,
            'nombre' => $siguienteUnidad->nombre,
        ] : null;

        $semestre = $unidad->semestre;
        $jsonName = "matematicas_{$semestre->numero}_u{$unidad->numero}.json";
        $filePath = storage_path("content/{$jsonName}");

        if (file_exists($filePath)) {
            $mtime = filemtime($filePath);
            $cacheKey = "unidad_contenido_{$unidad->id}_v{$mtime}";

            $result = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($unidad, $jsonName, $filePath, $semestre) {
                // Registrar o buscar en la base de datos la fila del contenido didáctico
                $contenido = Contenido::firstOrCreate(
                    [
                        'json_path' => "content/{$jsonName}",
                    ],
                    [
                        'tipo' => 'unidad',
                        'metadata' => [
                            'materia' => $semestre->materia->nombre,
                            'semestre' => $semestre->numero,
                            'unidad_nombre' => $unidad->nombre,
                        ]
                    ]
                );

                $jsonString = file_get_contents($filePath);
                $contentData = json_decode($jsonString, true);

                return [
                    'id' => $contenido->id,
                    'tipo' => $contenido->tipo,
                    'unidad' => [
                        'id' => $unidad->id,
                        'numero' => $unidad->numero,
                        'nombre' => $unidad->nombre,
                        'descripcion' => $unidad->descripcion,
                    ],
                    'contenido' => $contentData
                ];
            });

            // Inyectar información de la siguiente unidad
            $result['siguiente_unidad'] = $siguienteUnidadData;

            return response()->json($result);
        }

        // Si el archivo no existe, retornamos un contenido por defecto cacheado
        $cacheKeyFallback = "unidad_contenido_{$unidad->id}_fallback";
        $fallbackResult = \Illuminate\Support\Facades\Cache::remember($cacheKeyFallback, 86400, function () use ($unidad) {
            return [
                'id' => null,
                'tipo' => 'unidad',
                'unidad' => [
                    'id' => $unidad->id,
                    'numero' => $unidad->numero,
                    'nombre' => $unidad->nombre,
                    'descripcion' => $unidad->descripcion,
                ],
                'contenido' => [
                    'introduccion' => "El material didáctico para esta unidad está siendo redactado y validado por el profesor titular de la materia.",
                    'objetivos' => [
                        "Revisar los temas de la unidad: " . $unidad->nombre
                    ],
                    'conocimientos_previos' => [
                        "Haber acreditado el curso anterior o contar con bases sólidas."
                    ],
                    'explicacion' => [
                        [
                            'titulo' => 'Contenido en preparación',
                            'texto' => "Muy pronto estará disponible la explicación completa y detallada de los temas correspondientes a esta unidad de aprendizaje."
                        ]
                    ],
                    'conceptos_clave' => [],
                    'ejemplos' => [],
                    'errores_comunes' => [],
                    'ejercicios_guiados' => [],
                    'resumen' => "Contenido en phase de planeación pedagógica.",
                    'bibliografia' => []
                ]
            ];
        });

        // Inyectar información de la siguiente unidad
        $fallbackResult['siguiente_unidad'] = $siguienteUnidadData;

        return response()->json($fallbackResult);
    }
}
