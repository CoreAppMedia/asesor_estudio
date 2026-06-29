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

class SesionTest extends TestCase
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

        // Banco de preguntas
        for ($i = 1; $i <= 5; $i++) {
            Pregunta::create([
                'unidad_id' => $this->unidad->id,
                'texto_pregunta' => "Pregunta examen {$i}",
                'tipo_pregunta' => 'opcion_multiple',
                'opciones_json' => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
                'respuesta_correcta' => 'A'
            ]);
        }

        // Escuela
        $this->generacion = Generacion::create(['anio_inicio' => 2026, 'anio_fin' => 2027]);
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

        // Evaluación
        $this->evaluacion = Evaluacion::create([
            'nombre' => 'Examen Parcial 1',
            'unidad_id' => $this->unidad->id,
            'total_preguntas' => 3,
            'tiempo_limite_minutos' => 20,
        ]);
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    public function test_admin_can_crud_evaluations()
    {
        // List
        $response = $this->withHeaders($this->getHeaders())
            ->getJson('/api/evaluaciones');
        $response->assertStatus(200);

        // Create
        $response = $this->withHeaders($this->getHeaders())
            ->postJson('/api/evaluaciones', [
                'nombre' => 'Examen Unidad 1',
                'unidad_id' => $this->unidad->id,
                'total_preguntas' => 5,
                'tiempo_limite_minutos' => 30,
            ]);
        $response->assertStatus(201)
            ->assertJsonPath('nombre', 'Examen Unidad 1');

        $evalId = $response->json('id');

        // Update
        $response = $this->withHeaders($this->getHeaders())
            ->putJson("/api/evaluaciones/{$evalId}", [
                'nombre' => 'Examen Unidad 1 Modificado',
                'unidad_id' => $this->unidad->id,
                'total_preguntas' => 4,
                'tiempo_limite_minutos' => 40,
            ]);
        $response->assertStatus(200)
            ->assertJsonPath('nombre', 'Examen Unidad 1 Modificado')
            ->assertJsonPath('total_preguntas', 4);

        // Delete
        $response = $this->withHeaders($this->getHeaders())
            ->deleteJson("/api/evaluaciones/{$evalId}");
        $response->assertStatus(204);
    }

    public function test_admin_can_crud_sessions()
    {
        // Create session
        $response = $this->withHeaders($this->getHeaders())
            ->postJson('/api/sesiones', [
                'grupo_id' => $this->grupo->id,
                'evaluacion_id' => $this->evaluacion->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'codigo_acceso', 'activa']);
        
        $sesionId = $response->json('id');
        $codigo = $response->json('codigo_acceso');
        
        $this->assertEquals(5, strlen($codigo));

        // Toggle session active status (turn off)
        $response = $this->withHeaders($this->getHeaders())
            ->postJson("/api/sesiones/{$sesionId}/toggle");
        
        $response->assertStatus(200)
            ->assertJsonPath('activa', false);
        $this->assertNotNull($response->json('fecha_cierre'));

        // Toggle again (turn on)
        $response = $this->withHeaders($this->getHeaders())
            ->postJson("/api/sesiones/{$sesionId}/toggle");
        
        $response->assertStatus(200)
            ->assertJsonPath('activa', true);
        $this->assertNull($response->json('fecha_cierre'));

        // Delete session
        $response = $this->withHeaders($this->getHeaders())
            ->deleteJson("/api/sesiones/{$sesionId}");
        $response->assertStatus(204);
    }

    public function test_student_can_lookup_active_session()
    {
        $sesion = Sesion::create([
            'grupo_id' => $this->grupo->id,
            'evaluacion_id' => $this->evaluacion->id,
            'codigo_acceso' => 'TEST1',
            'activa' => true,
        ]);

        // Search active
        $response = $this->getJson("/api/public/sesiones/buscar/test1");
        $response->assertStatus(200)
            ->assertJsonPath('grupo', '401A')
            ->assertJsonCount(1, 'alumnos')
            ->assertJsonPath('alumnos.0.nombre', 'Juan');

        // Close session
        $sesion->update(['activa' => false]);

        // Search inactive
        $response = $this->getJson("/api/public/sesiones/buscar/test1");
        $response->assertStatus(403)
            ->assertJsonPath('message', 'La sesión de evaluación ha sido cerrada por el profesor.');
    }

    public function test_student_can_start_session_attempt()
    {
        $sesion = Sesion::create([
            'grupo_id' => $this->grupo->id,
            'evaluacion_id' => $this->evaluacion->id,
            'codigo_acceso' => 'TEST2',
            'activa' => true,
        ]);

        $response = $this->postJson("/api/public/sesiones/iniciar", [
            'codigo_acceso' => 'test2',
            'alumno_id' => $this->alumno->id,
            'celular_ultimos_cuatro' => '5678',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('reanudado', false)
            ->assertJsonCount(3, 'preguntas')
            ->assertJsonMissing(['respuesta_correcta']);

        $intentoId = $response->json('intento_id');
        $this->assertDatabaseHas('intentos', [
            'id' => $intentoId,
            'alumno_id' => $this->alumno->id,
            'sesion_id' => $sesion->id
        ]);

        $this->assertDatabaseCount('resultados', 3);
    }

    public function test_student_cannot_start_attempt_if_already_completed()
    {
        $sesion = Sesion::create([
            'grupo_id' => $this->grupo->id,
            'evaluacion_id' => $this->evaluacion->id,
            'codigo_acceso' => 'TEST3',
            'activa' => true,
        ]);

        // Create completed attempt
        Intento::create([
            'alumno_id' => $this->alumno->id,
            'sesion_id' => $sesion->id,
            'numero_intento' => 1,
            'iniciado_at' => now(),
            'finalizado_at' => now(),
        ]);

        $response = $this->postJson("/api/public/sesiones/iniciar", [
            'codigo_acceso' => 'test3',
            'alumno_id' => $this->alumno->id,
            'celular_ultimos_cuatro' => '5678',
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('message', 'Ya has completado y finalizado tu evaluación para esta sesión.');
    }

    public function test_student_resumes_attempt_if_active()
    {
        $sesion = Sesion::create([
            'grupo_id' => $this->grupo->id,
            'evaluacion_id' => $this->evaluacion->id,
            'codigo_acceso' => 'TEST4',
            'activa' => true,
        ]);

        // Start first attempt
        $response1 = $this->postJson("/api/public/sesiones/iniciar", [
            'codigo_acceso' => 'test4',
            'alumno_id' => $this->alumno->id,
            'celular_ultimos_cuatro' => '5678',
        ]);
        $response1->assertStatus(201);
        $intentoId1 = $response1->json('intento_id');

        // Start second time (resume)
        $response2 = $this->postJson("/api/public/sesiones/iniciar", [
            'codigo_acceso' => 'test4',
            'alumno_id' => $this->alumno->id,
            'celular_ultimos_cuatro' => '5678',
        ]);

        $response2->assertStatus(200)
            ->assertJsonPath('reanudado', true)
            ->assertJsonPath('intento_id', $intentoId1)
            ->assertJsonCount(3, 'preguntas');
    }
}
