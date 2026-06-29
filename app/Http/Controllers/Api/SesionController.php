<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sesion;
use App\Models\Evaluacion;
use App\Models\Alumno;
use App\Models\Intento;
use App\Models\Resultado;
use App\Models\Pregunta;
use Illuminate\Http\Request;

class SesionController extends Controller
{
    // === Endpoints Administrativos ===

    public function index()
    {
        $sesiones = Sesion::with([
            'grupo.generacion', 
            'evaluacion.unidad.semestre.materia'
        ])
        ->withCount([
            'intentos as total_intentos',
            'intentos as intentos_completados' => function ($query) {
                $query->whereNotNull('finalizado_at');
            }
        ])
        ->orderBy('id', 'desc')
        ->get();

        return response()->json($sesiones);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'evaluacion_id' => 'required|exists:evaluaciones,id',
        ]);

        // Generar un código único de 5 caracteres alfanuméricos
        $codigo = null;
        do {
            $codigo = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5));
        } while (Sesion::where('codigo_acceso', $codigo)->exists());

        $sesion = Sesion::create([
            'grupo_id' => $validated['grupo_id'],
            'evaluacion_id' => $validated['evaluacion_id'],
            'codigo_acceso' => $codigo,
            'activa' => true,
            'fecha_cierre' => null,
        ]);

        return response()->json($sesion->load([
            'grupo.generacion', 
            'evaluacion.unidad.semestre.materia'
        ]), 201);
    }

    public function toggle(Sesion $sesion)
    {
        $sesion->activa = !$sesion->activa;
        if (!$sesion->activa) {
            $sesion->fecha_cierre = now();
        } else {
            $sesion->fecha_cierre = null;
        }
        $sesion->save();

        return response()->json($sesion->load([
            'grupo.generacion', 
            'evaluacion.unidad.semestre.materia'
        ]));
    }

    public function destroy(Sesion $sesion)
    {
        $sesion->delete();
        return response()->json(null, 204);
    }

    // === Endpoints Públicos de Alumnos ===

    public function buscar($codigo)
    {
        $codigo = strtoupper(trim($codigo));

        $sesion = Sesion::where('codigo_acceso', $codigo)
            ->with([
                'evaluacion.unidad.semestre.materia',
                'grupo.alumnos' => function ($query) {
                    $query->orderBy('numero_lista', 'asc');
                }
            ])
            ->first();

        if (!$sesion) {
            return response()->json([
                'message' => 'Código de acceso no válido o no existe.'
            ], 404);
        }

        if (!$sesion->activa) {
            return response()->json([
                'message' => 'La sesión de evaluación ha sido cerrada por el profesor.'
            ], 403);
        }

        return response()->json([
            'sesion_id' => $sesion->id,
            'codigo_acceso' => $sesion->codigo_acceso,
            'grupo' => $sesion->grupo->nombre,
            'evaluacion' => [
                'id' => $sesion->evaluacion->id,
                'nombre' => $sesion->evaluacion->nombre,
                'tiempo_limite_minutos' => $sesion->evaluacion->tiempo_limite_minutos,
                'total_preguntas' => $sesion->evaluacion->total_preguntas,
            ],
            'alumnos' => $sesion->grupo->alumnos->map(function ($alumno) {
                return [
                    'id' => $alumno->id,
                    'numero_lista' => $alumno->numero_lista,
                    'nombre' => $alumno->nombre,
                    'apellido' => trim($alumno->apellido_paterno . ' ' . $alumno->apellido_materno),
                ];
            })
        ]);
    }

    public function iniciar(Request $request)
    {
        $validated = $request->validate([
            'codigo_acceso' => 'required|string',
            'alumno_id' => 'required|exists:alumnos,id',
            'celular_ultimos_cuatro' => 'required|string|size:4',
        ]);

        $codigo = strtoupper(trim($validated['codigo_acceso']));
        $alumnoId = $validated['alumno_id'];

        $sesion = Sesion::where('codigo_acceso', $codigo)->first();

        if (!$sesion) {
            return response()->json(['message' => 'Sesión no encontrada.'], 404);
        }

        if (!$sesion->activa) {
            return response()->json(['message' => 'Esta sesión se encuentra inactiva.'], 403);
        }

        $alumno = Alumno::findOrFail($alumnoId);

        // Validar que el alumno pertenezca al grupo de la sesión
        if ($alumno->grupo_id !== $sesion->grupo_id) {
            return response()->json([
                'message' => 'El alumno seleccionado no pertenece al grupo de esta evaluación.'
            ], 400);
        }

        // Validar últimos 4 dígitos del celular
        if (empty($alumno->numero_celular)) {
            return response()->json([
                'message' => 'Tu número de celular no está registrado en el sistema. Por favor, regístrate en el grupo usando el código QR o solicita al profesor registrar tu celular antes de iniciar el examen.'
            ], 403);
        }

        $ultimosCuatro = substr(trim($alumno->numero_celular), -4);
        if ($validated['celular_ultimos_cuatro'] !== $ultimosCuatro) {
            return response()->json([
                'message' => 'Los últimos 4 dígitos del celular son incorrectos.'
            ], 403);
        }

        // Validar si el alumno ya completó un intento
        $intentoFinalizado = Intento::where('sesion_id', $sesion->id)
            ->where('alumno_id', $alumnoId)
            ->whereNotNull('finalizado_at')
            ->first();

        if ($intentoFinalizado) {
            return response()->json([
                'message' => 'Ya has completado y finalizado tu evaluación para esta sesión.'
            ], 403);
        }

        // Validar si el alumno tiene un intento activo (incompleto) para reanudar
        $intentoActivo = Intento::where('sesion_id', $sesion->id)
            ->where('alumno_id', $alumnoId)
            ->whereNull('finalizado_at')
            ->first();

        if ($intentoActivo) {
            // Asegurar que tenga session_token
            if (!$intentoActivo->session_token) {
                $intentoActivo->update(['session_token' => \Illuminate\Support\Str::random(40)]);
            }

            // Cargar preguntas asociadas a este intento activo desde la tabla resultados
            $resultadoPreguntas = Resultado::where('intento_id', $intentoActivo->id)
                ->with('pregunta')
                ->orderBy('id', 'asc')
                ->get();

            $preguntasPayload = $resultadoPreguntas->map(function ($res) {
                return [
                    'id' => $res->pregunta->id,
                    'texto_pregunta' => $res->pregunta->texto_pregunta,
                    'tipo_pregunta' => $res->pregunta->tipo_pregunta,
                    'opciones_json' => $res->pregunta->opciones_json,
                    'respuesta_guardada' => $res->respuesta_alumno
                ];
            });

            return response()->json([
                'intento_id' => $intentoActivo->id,
                'session_token' => $intentoActivo->session_token,
                'reanudado' => true,
                'alumno' => [
                    'id' => $alumno->id,
                    'nombre' => $alumno->nombre . ' ' . trim($alumno->apellido_paterno . ' ' . $alumno->apellido_materno)
                ],
                'evaluacion' => [
                    'nombre' => $sesion->evaluacion->nombre,
                    'tiempo_limite_minutos' => $sesion->evaluacion->tiempo_limite_minutos,
                ],
                'preguntas' => $preguntasPayload,
                'iniciado_at' => $intentoActivo->iniciado_at,
            ]);
        }

        // Si es un intento nuevo, seleccionar preguntas de forma aleatoria
        $totalPreguntasExamen = $sesion->evaluacion->total_preguntas;
        $preguntas = Pregunta::where('unidad_id', $sesion->evaluacion->unidad_id)
            ->inRandomOrder()
            ->take($totalPreguntasExamen)
            ->get();

        if ($preguntas->isEmpty()) {
            return response()->json([
                'message' => 'No hay preguntas disponibles en el banco para la unidad de esta evaluación.'
            ], 404);
        }

        // Generar un session_token
        $sessionToken = \Illuminate\Support\Str::random(40);

        // Crear el intento
        $intento = Intento::create([
            'alumno_id' => $alumnoId,
            'sesion_id' => $sesion->id,
            'numero_intento' => 1,
            'iniciado_at' => now(),
            'finalizado_at' => null,
            'session_token' => $sessionToken,
        ]);

        // Registrar las preguntas en la tabla de resultados como placeholders
        foreach ($preguntas as $pregunta) {
            Resultado::create([
                'intento_id' => $intento->id,
                'pregunta_id' => $pregunta->id,
                'respuesta_alumno' => '', // placeholder
                'es_correcta' => null,
                'puntaje' => null,
            ]);
        }

        // Preparar payload de preguntas sin la respuesta correcta
        $preguntasPayload = $preguntas->map(function ($p) {
            return [
                'id' => $p->id,
                'texto_pregunta' => $p->texto_pregunta,
                'tipo_pregunta' => $p->tipo_pregunta,
                'opciones_json' => $p->opciones_json,
                'respuesta_guardada' => ''
            ];
        });

        return response()->json([
            'intento_id' => $intento->id,
            'session_token' => $sessionToken,
            'reanudado' => false,
            'alumno' => [
                'id' => $alumno->id,
                'nombre' => $alumno->nombre . ' ' . trim($alumno->apellido_paterno . ' ' . $alumno->apellido_materno)
            ],
            'evaluacion' => [
                'nombre' => $sesion->evaluacion->nombre,
                'tiempo_limite_minutos' => $sesion->evaluacion->tiempo_limite_minutos,
            ],
            'preguntas' => $preguntasPayload,
            'iniciado_at' => $intento->iniciado_at,
        ], 201);
    }

    public function guardarRespuesta(Request $request, Intento $intento)
    {
        // Validar X-Session-Token
        $token = $request->header('X-Session-Token');
        if (!$token || $token !== $intento->session_token) {
            return response()->json(['message' => 'Acceso no autorizado a este intento de examen.'], 403);
        }

        if ($intento->finalizado_at) {
            return response()->json(['message' => 'Este examen ya ha sido finalizado.'], 403);
        }

        $sesion = $intento->sesion;
        if (!$sesion->activa) {
            $intento->update(['finalizado_at' => now()]);
            $this->autocalificarIntento($intento);
            return response()->json(['message' => 'La sesión ha sido cerrada por el profesor. Examen finalizado automáticamente.'], 403);
        }

        $tiempoLimite = $sesion->evaluacion->tiempo_limite_minutos;
        $iniciado = $intento->iniciado_at;
        $expira = $iniciado->copy()->addMinutes($tiempoLimite);

        if (now()->greaterThan($expira)) {
            $intento->update(['finalizado_at' => $expira]);
            $this->autocalificarIntento($intento);
            return response()->json([
                'message' => 'El tiempo límite de resolución ha expirado. Tu examen se envió automáticamente.',
                'tiempo_expirado' => true
            ], 403);
        }

        $validated = $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'respuesta' => 'nullable|string',
        ]);

        $preguntaId = $validated['pregunta_id'];
        $respuesta = $validated['respuesta'] ?? '';

        $resultado = Resultado::where('intento_id', $intento->id)
            ->where('pregunta_id', $preguntaId)
            ->first();

        if (!$resultado) {
            return response()->json(['message' => 'Esta pregunta no pertenece a esta evaluación.'], 400);
        }

        $resultado->update([
            'respuesta_alumno' => $respuesta
        ]);

        return response()->json(['success' => true]);
    }

    public function finalizar(Request $request, Intento $intento)
    {
        // Validar X-Session-Token
        $token = $request->header('X-Session-Token');
        if (!$token || $token !== $intento->session_token) {
            return response()->json(['message' => 'Acceso no autorizado a este intento de examen.'], 403);
        }

        if ($intento->finalizado_at) {
            return response()->json(['message' => 'Este examen ya ha sido finalizado.'], 400);
        }

        // Validar los últimos 4 dígitos del celular
        $validated = $request->validate([
            'celular_ultimos_cuatro' => 'required|string|size:4'
        ]);

        $alumno = $intento->alumno;
        if (empty($alumno->numero_celular)) {
            return response()->json([
                'message' => 'Tu número de celular no está registrado en el sistema. Contacta al profesor.'
            ], 403);
        }

        $ultimosCuatro = substr(trim($alumno->numero_celular), -4);
        if ($validated['celular_ultimos_cuatro'] !== $ultimosCuatro) {
            return response()->json([
                'message' => 'Los últimos 4 dígitos del celular son incorrectos.'
            ], 403);
        }

        $intento->update([
            'finalizado_at' => now()
        ]);

        $this->autocalificarIntento($intento);

        return response()->json([
            'message' => 'Evaluación finalizada y enviada con éxito.'
        ]);
    }

    private function autocalificarIntento(Intento $intento)
    {
        $resultados = Resultado::where('intento_id', $intento->id)->with('pregunta')->get();
        foreach ($resultados as $res) {
            $pregunta = $res->pregunta;
            if ($pregunta->tipo_pregunta === 'opcion_multiple') {
                $esCorrecta = (strtolower(trim($res->respuesta_alumno)) === strtolower(trim($pregunta->respuesta_correcta)));
                $res->update([
                    'es_correcta' => $esCorrecta,
                    'puntaje' => $esCorrecta ? 1.00 : 0.00
                ]);
            } else {
                $res->update([
                    'es_correcta' => null,
                    'puntaje' => null
                ]);
            }
        }
    }

    public function obtenerIntentos(Sesion $sesion)
    {
        $alumnos = Alumno::where('grupo_id', $sesion->grupo_id)
            ->orderBy('numero_lista', 'asc')
            ->get();

        $intentos = Intento::where('sesion_id', $sesion->id)
            ->with('resultados.pregunta')
            ->get()
            ->keyBy('alumno_id');


        $result = $alumnos->map(function ($alumno) use ($intentos) {
            $intento = $intentos->get($alumno->id);
            
            $estado = 'No iniciado';
            $score = null;
            $calificado = true;
            $tieneAbiertas = false;
            $abiertas_pendientes = 0;

            if ($intento) {
                if ($intento->finalizado_at) {
                    $estado = 'Finalizado';

                    foreach ($intento->resultados as $res) {
                        if ($res->es_correcta === null && $res->puntaje === null) {
                            $calificado = false;
                        }
                    }

                    // Detectar preguntas abiertas sin calificar
                    $abiertas_pendientes = $intento->resultados
                        ->filter(function ($res) {
                            return $res->pregunta &&
                                   $res->pregunta->tipo_pregunta === 'respuesta_abierta' &&
                                   $res->es_correcta === null;
                        })->count();
                    $tieneAbiertas = $abiertas_pendientes > 0;

                    $totalPreguntas = $intento->resultados->count();
                    // Para el score solo usar preguntas ya calificadas
                    $resultadosCalificados = $intento->resultados->whereNotNull('puntaje');
                    $sumaPuntajes = $resultadosCalificados->sum('puntaje');
                    $score = $totalPreguntas > 0 ? round(($sumaPuntajes / $totalPreguntas) * 10, 1) : 0;
                } else {
                    $estado = 'En curso';
                }
            }

            return [
                'alumno_id' => $alumno->id,
                'numero_lista' => $alumno->numero_lista,
                'nombre' => $alumno->nombre . ' ' . trim($alumno->apellido_paterno . ' ' . $alumno->apellido_materno),
                'intento' => $intento ? [
                    'id' => $intento->id,
                    'iniciado_at' => $intento->iniciado_at,
                    'finalizado_at' => $intento->finalizado_at,
                    'estado' => $estado,
                    'calificado' => $calificado,
                    'tiene_abiertas_pendientes' => $tieneAbiertas,
                    'abiertas_pendientes' => $abiertas_pendientes,
                    'score' => $score,
                    'total_preguntas' => $intento->resultados->count()
                ] : null
            ];
        });

        return response()->json($result);
    }

    public function obtenerIntentoDetalle(Sesion $sesion, Intento $intento)
    {
        if ($intento->sesion_id !== $sesion->id) {
            return response()->json(['message' => 'El intento no corresponde a la sesión.'], 400);
        }

        $intento->load(['alumno', 'resultados.pregunta']);

        return response()->json($intento);
    }

    public function guardarCalificacion(Request $request, Intento $intento)
    {
        $validated = $request->validate([
            'resultados' => 'required|array',
            'resultados.*.id' => 'required|exists:resultados,id',
            'resultados.*.es_correcta' => 'required|boolean',
            'resultados.*.puntaje' => 'required|numeric|min:0|max:1',
            'resultados.*.feedback_profesor' => 'nullable|string',
        ]);

        foreach ($validated['resultados'] as $resData) {
            $resultado = Resultado::where('intento_id', $intento->id)
                ->where('id', $resData['id'])
                ->with('pregunta')
                ->first();

            if (!$resultado) continue;

            // Las preguntas de opción múltiple son calificadas automáticamente.
            // Solo permitir actualizar el feedback_profesor para ellas, no la calificación.
            if ($resultado->pregunta && $resultado->pregunta->tipo_pregunta === 'opcion_multiple') {
                $resultado->update([
                    'feedback_profesor' => $resData['feedback_profesor'] ?? null
                ]);
                continue;
            }

            // Preguntas de respuesta abierta: el profesor define la calificación
            $resultado->update([
                'es_correcta' => $resData['es_correcta'],
                'puntaje' => $resData['puntaje'],
                'feedback_profesor' => $resData['feedback_profesor'] ?? null
            ]);
        }

        return response()->json(['message' => 'Calificación guardada exitosamente.']);
    }

    public function obtenerEstadisticas(Sesion $sesion)
    {
        $totalAlumnos = Alumno::where('grupo_id', $sesion->grupo_id)->count();
        $intentos = Intento::where('sesion_id', $sesion->id)->with('resultados')->get();
        
        $enCursoCount = $intentos->whereNull('finalizado_at')->count();
        $finalizadoCount = $intentos->whereNotNull('finalizado_at')->count();
        $noIniciadoCount = max(0, $totalAlumnos - ($enCursoCount + $finalizadoCount));

        $sumaPromedios = 0;
        $distribucion = [
            'excelente' => 0, // 9-10
            'bueno' => 0,     // 8-8.9
            'regular' => 0,   // 7-7.9
            'suficiente' => 0,// 6-6.9
            'insuficiente'=> 0 // 0-5.9
        ];

        foreach ($intentos as $intento) {
            if (!$intento->finalizado_at) continue;

            $totalPreguntas = $intento->resultados->count();
            $sumaPuntajes = $intento->resultados->sum('puntaje');
            $score = $totalPreguntas > 0 ? ($sumaPuntajes / $totalPreguntas) * 10 : 0;

            $sumaPromedios += $score;

            if ($score >= 9) {
                $distribucion['excelente']++;
            } elseif ($score >= 8) {
                $distribucion['bueno']++;
            } elseif ($score >= 7) {
                $distribucion['regular']++;
            } elseif ($score >= 6) {
                $distribucion['suficiente']++;
            } else {
                $distribucion['insuficiente']++;
            }
        }

        $promedioGrupal = $finalizadoCount > 0 ? round($sumaPromedios / $finalizadoCount, 1) : 0;

        $preguntas = Pregunta::where('unidad_id', $sesion->evaluacion->unidad_id)->get();
        
        $resultadosFinalizados = Resultado::whereIn('intento_id', $intentos->whereNotNull('finalizado_at')->pluck('id'))
            ->get()
            ->groupBy('pregunta_id');

        $preguntasEstadisticas = $preguntas->map(function ($pregunta) use ($resultadosFinalizados) {
            $resultados = $resultadosFinalizados->get($pregunta->id) ?: collect();
            $totalRespuestas = $resultados->count();
            $correctas = $resultados->where('es_correcta', true)->count();
            $incorrectas = $totalRespuestas - $correctas;
            
            $tasaError = $totalRespuestas > 0 ? round(($incorrectas / $totalRespuestas) * 100, 1) : 0;

            return [
                'pregunta_id' => $pregunta->id,
                'texto_pregunta' => $pregunta->texto_pregunta,
                'tipo_pregunta' => $pregunta->tipo_pregunta,
                'total_respuestas' => $totalRespuestas,
                'correctas' => $correctas,
                'incorrectas' => $incorrectas,
                'tasa_error' => $tasaError,
            ];
        })->sortByDesc('tasa_error')->values()->all();

        return response()->json([
            'resumen_intentos' => [
                'total_alumnos' => $totalAlumnos,
                'no_iniciado' => $noIniciadoCount,
                'en_curso' => $enCursoCount,
                'finalizado' => $finalizadoCount
            ],
            'promedio_grupal' => $promedioGrupal,
            'distribucion' => $distribucion,
            'preguntas_estadisticas' => $preguntasEstadisticas
        ]);
    }

    public function exportarCSV(Sesion $sesion)
    {
        $sesion->load(['grupo', 'evaluacion']);

        $alumnos = Alumno::where('grupo_id', $sesion->grupo_id)
            ->orderBy('numero_lista', 'asc')
            ->get();

        $intentos = Intento::where('sesion_id', $sesion->id)
            ->with('resultados')
            ->get()
            ->keyBy('alumno_id');

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=calificaciones_grupo_{$sesion->grupo->nombre}_sesion_{$sesion->id}.csv",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($alumnos, $intentos) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['Número Lista', 'Apellidos', 'Nombres', 'Estatus Examen', 'Calificación Definitiva']);

            foreach ($alumnos as $alumno) {
                $intento = $intentos->get($alumno->id);
                $estado = 'No iniciado';
                $score = 'N/A';

                if ($intento) {
                    if ($intento->finalizado_at) {
                        $estado = 'Finalizado';
                        
                        $totalPreguntas = $intento->resultados->count();
                        $sumaPuntajes = $intento->resultados->sum('puntaje');
                        $score = $totalPreguntas > 0 ? round(($sumaPuntajes / $totalPreguntas) * 10, 1) : 0;
                    } else {
                        $estado = 'En curso';
                    }
                }

                fputcsv($file, [
                    $alumno->numero_lista,
                    trim($alumno->apellido_paterno . ' ' . $alumno->apellido_materno),
                    $alumno->nombre,
                    $estado,
                    $score
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
