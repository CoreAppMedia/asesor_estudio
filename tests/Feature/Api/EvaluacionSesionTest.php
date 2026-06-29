<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Materia;
use App\Models\Semestre;
use App\Models\Unidad;
use App\Models\Pregunta;
use App\Models\Generacion;
use App\Models\Grupo;
use App\Models\Alumno;
use App\Models\Evaluacion;
use App\Models\Sesion;
use App\Models\Intento;
use App\Models\Resultado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluacionSesionTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;
    private $materia;
    private $semestre;
    private $unidad;
    private $generacion;
    private $grupo;
    private $alumno;
    private $evaluacion;
    private $sesion;
    private $preguntaMC;
    private $preguntaOpen;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin & Auth
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;

        // Academics & Catalog
        $this->materia = Materia::create(['nombre' => 'Matemáticas CCH']);
        $this->semestre = Semestre::create(['materia_id' => $this->materia->id, 'numero' => 1]);
        $this->unidad = Unidad::create([
            'semestre_id' => $this->semestre->id,
            'numero' => 1,
            'nombre' => 'Unidad 1',
        ]);

        // Pregunta 1: Opción Múltiple
        $this->preguntaMC = Pregunta::create([
            'unidad_id' => $this->unidad->id,
            'texto_pregunta' => "Pregunta Opción Múltiple",
            'tipo_pregunta' => 'opcion_multiple',
            'opciones_json' => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
            'respuesta_correcta' => 'A'
        ]);

        // Pregunta 2: Abierta
        $this->preguntaOpen = Pregunta::create([
            'unidad_id' => $this->unidad->id,
            'texto_pregunta' => "Pregunta Abierta",
            'tipo_pregunta' => 'respuesta_abierta',
            'opciones_json' => null,
            'respuesta_correcta' => 'Desarrollo detallado'
        ]);

        // Escuela
        $this->generacion = Generacion::create(['generacion_id' => 1, 'anio_inicio' => 2026, 'anio_fin' => 2027]);
        $this->grupo = Grupo::create([
            'generacion_id' => $this->generacion->id,
            'nombre' => '401A',
        ]);
        $this->alumno = Alumno::create([
            'grupo_id' => $this->grupo->id,
            'numero_lista' => 1,
            'nombre' => 'Juan',
            'apellido_paterno' => 'Pérez',
            'numero_celular' => '5512345678',
        ]);

        // Evaluación (2 preguntas)
        $this->evaluacion = Evaluacion::create([
            'nombre' => 'Examen Parcial',
            'unidad_id' => $this->unidad->id,
            'total_preguntas' => 2,
            'tiempo_limite_minutos' => 10,
        ]);

        // Sesión
        $this->sesion = Sesion::create([
            'grupo_id' => $this->grupo->id,
            'evaluacion_id' => $this->evaluacion->id,
            'codigo_acceso' => 'TESTX',
            'activa' => true,
        ]);
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    public function test_student_can_save_partial_answers()
    {
        // Iniciar intento
        $intento = Intento::create([
            'alumno_id' => $this->alumno->id,
            'sesion_id' => $this->sesion->id,
            'numero_intento' => 1,
            'iniciado_at' => now(),
            'session_token' => 'test-token',
        ]);

        Resultado::create([
            'intento_id' => $intento->id,
            'pregunta_id' => $this->preguntaMC->id,
            'respuesta_alumno' => '',
        ]);

        // Guardar respuesta
        $response = $this->withHeaders(['X-Session-Token' => 'test-token'])
            ->postJson("/api/public/intentos/{$intento->id}/respuesta", [
                'pregunta_id' => $this->preguntaMC->id,
                'respuesta' => 'A'
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('resultados', [
            'intento_id' => $intento->id,
            'pregunta_id' => $this->preguntaMC->id,
            'respuesta_alumno' => 'A'
        ]);
    }

    public function test_student_cannot_save_answer_if_expired()
    {
        // Iniciar intento de hace 15 minutos (límite 10 minutos)
        $intento = Intento::create([
            'alumno_id' => $this->alumno->id,
            'sesion_id' => $this->sesion->id,
            'numero_intento' => 1,
            'iniciado_at' => now()->subMinutes(15),
            'session_token' => 'test-token',
        ]);

        Resultado::create([
            'intento_id' => $intento->id,
            'pregunta_id' => $this->preguntaMC->id,
            'respuesta_alumno' => '',
        ]);

        // Intentar guardar respuesta
        $response = $this->withHeaders(['X-Session-Token' => 'test-token'])
            ->postJson("/api/public/intentos/{$intento->id}/respuesta", [
                'pregunta_id' => $this->preguntaMC->id,
                'respuesta' => 'A'
            ]);

        $response->assertStatus(403)
            ->assertJsonPath('tiempo_expirado', true);

        // Validar que el intento haya sido cerrado
        $intento->refresh();
        $this->assertNotNull($intento->finalizado_at);
    }

    public function test_student_cannot_save_answer_if_session_closed()
    {
        $intento = Intento::create([
            'alumno_id' => $this->alumno->id,
            'sesion_id' => $this->sesion->id,
            'numero_intento' => 1,
            'iniciado_at' => now(),
            'session_token' => 'test-token',
        ]);

        Resultado::create([
            'intento_id' => $intento->id,
            'pregunta_id' => $this->preguntaMC->id,
            'respuesta_alumno' => '',
        ]);

        // Cerrar sesión
        $this->sesion->update(['activa' => false]);

        // Intentar guardar respuesta
        $response = $this->withHeaders(['X-Session-Token' => 'test-token'])
            ->postJson("/api/public/intentos/{$intento->id}/respuesta", [
                'pregunta_id' => $this->preguntaMC->id,
                'respuesta' => 'A'
            ]);

        $response->assertStatus(403);

        $intento->refresh();
        $this->assertNotNull($intento->finalizado_at);
    }

    public function test_student_can_finalize_and_triggers_autograding()
    {
        $intento = Intento::create([
            'alumno_id' => $this->alumno->id,
            'sesion_id' => $this->sesion->id,
            'numero_intento' => 1,
            'iniciado_at' => now(),
            'session_token' => 'test-token',
        ]);

        // Crear resultados precargados
        $resMC = Resultado::create([
            'intento_id' => $intento->id,
            'pregunta_id' => $this->preguntaMC->id,
            'respuesta_alumno' => 'A', // Correcta
        ]);

        $resOpen = Resultado::create([
            'intento_id' => $intento->id,
            'pregunta_id' => $this->preguntaOpen->id,
            'respuesta_alumno' => 'Respuesta del alumno',
        ]);

        // Finalizar examen
        $response = $this->withHeaders(['X-Session-Token' => 'test-token'])
            ->postJson("/api/public/intentos/{$intento->id}/finalizar", [
                'celular_ultimos_cuatro' => '5678',
            ]);
        $response->assertStatus(200);

        $intento->refresh();
        $this->assertNotNull($intento->finalizado_at);

        // Verificar autocalificación de la de opción múltiple
        $resMC->refresh();
        $this->assertTrue($resMC->es_correcta);
        $this->assertEquals(1.00, $resMC->puntaje);

        // Verificar que la de opción abierta queda en nula
        $resOpen->refresh();
        $this->assertNull($resOpen->es_correcta);
        $this->assertNull($resOpen->puntaje);
    }

    public function test_teacher_can_view_attempts_and_grade_open_questions()
    {
        $intento = Intento::create([
            'alumno_id' => $this->alumno->id,
            'sesion_id' => $this->sesion->id,
            'numero_intento' => 1,
            'iniciado_at' => now(),
            'finalizado_at' => now(),
        ]);

        $resMC = Resultado::create([
            'intento_id' => $intento->id,
            'pregunta_id' => $this->preguntaMC->id,
            'respuesta_alumno' => 'B', // Incorrecta
            'es_correcta' => false,
            'puntaje' => 0.00
        ]);

        $resOpen = Resultado::create([
            'intento_id' => $intento->id,
            'pregunta_id' => $this->preguntaOpen->id,
            'respuesta_alumno' => 'Respuesta abierta del alumno',
            'es_correcta' => null,
            'puntaje' => null
        ]);

        // 1. Docente obtiene la lista de intentos
        $response = $this->withHeaders($this->getHeaders())
            ->getJson("/api/sesiones/{$this->sesion->id}/intentos");

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonPath('0.intento.calificado', false); // No calificado porque falta la abierta

        // 2. Docente obtiene el detalle del intento
        $response = $this->withHeaders($this->getHeaders())
            ->getJson("/api/sesiones/{$this->sesion->id}/intentos/{$intento->id}");

        $response->assertStatus(200)
            ->assertJsonPath('alumno.nombre', 'Juan');

        // 3. Docente califica el examen (pregunta abierta)
        $response = $this->withHeaders($this->getHeaders())
            ->postJson("/api/intentos/{$intento->id}/calificar", [
                'resultados' => [
                    [
                        'id' => $resOpen->id,
                        'es_correcta' => true,
                        'puntaje' => 1.00,
                        'feedback_profesor' => 'Excelente desarrollo matemático.'
                    ]
                ]
            ]);

        $response->assertStatus(200);

        // Verificar en BD
        $resOpen->refresh();
        $this->assertTrue($resOpen->es_correcta);
        $this->assertEquals(1.00, $resOpen->puntaje);
        $this->assertEquals('Excelente desarrollo matemático.', $resOpen->feedback_profesor);

        // Verificar que ahora se reporta como calificado
        $response = $this->withHeaders($this->getHeaders())
            ->getJson("/api/sesiones/{$this->sesion->id}/intentos");
        
        $response->assertStatus(200)
            ->assertJsonPath('0.intento.calificado', true)
            ->assertJsonPath('0.intento.score', 5); // 1 acierto de 2 preguntas = 5 de 10
    }

    public function test_teacher_can_get_session_statistics()
    {
        $intento1 = Intento::create([
            'alumno_id' => $this->alumno->id,
            'sesion_id' => $this->sesion->id,
            'numero_intento' => 1,
            'iniciado_at' => now(),
            'finalizado_at' => now(),
        ]);
        Resultado::create([
            'intento_id' => $intento1->id,
            'pregunta_id' => $this->preguntaMC->id,
            'respuesta_alumno' => 'A',
            'es_correcta' => true,
            'puntaje' => 1.00
        ]);
        Resultado::create([
            'intento_id' => $intento1->id,
            'pregunta_id' => $this->preguntaOpen->id,
            'respuesta_alumno' => 'Respuesta',
            'es_correcta' => true,
            'puntaje' => 1.00
        ]);

        $response = $this->withHeaders($this->getHeaders())
            ->getJson("/api/sesiones/{$this->sesion->id}/estadisticas");

        $response->assertStatus(200)
            ->assertJsonPath('promedio_grupal', 10)
            ->assertJsonPath('resumen_intentos.finalizado', 1)
            ->assertJsonPath('distribucion.excelente', 1)
            ->assertJsonStructure(['resumen_intentos', 'promedio_grupal', 'distribucion', 'preguntas_estadisticas']);
    }

    public function test_teacher_can_export_session_grades_csv()
    {
        $response = $this->withHeaders($this->getHeaders())
            ->getJson("/api/sesiones/{$this->sesion->id}/exportar");

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=utf-8');
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('Juan', $content);
        $this->assertStringContainsString('Pérez', $content);
        $this->assertStringContainsString('Número Lista', $content);
    }
}
