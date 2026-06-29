<?php

namespace Tests\Feature\Api;

use App\Models\Materia;
use App\Models\Semestre;
use App\Models\Unidad;
use App\Models\Pregunta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CuestionarioTest extends TestCase
{
    use RefreshDatabase;

    private $unidad;

    protected function setUp(): void
    {
        parent::setUp();

        $materia = Materia::create(['nombre' => 'Matemáticas CCH']);
        $semestre = Semestre::create(['materia_id' => $materia->id, 'numero' => 1]);
        $this->unidad = Unidad::create([
            'semestre_id' => $semestre->id,
            'numero' => 1,
            'nombre' => 'Unidad 1',
        ]);

        // Crear 6 preguntas en la base de datos
        for ($i = 1; $i <= 6; $i++) {
            Pregunta::create([
                'unidad_id' => $this->unidad->id,
                'texto_pregunta' => "Pregunta {$i}",
                'tipo_pregunta' => 'opcion_multiple',
                'opciones_json' => ['A' => 'Opción A', 'B' => 'Opción B', 'C' => 'Opción C', 'D' => 'Opción D'],
                'respuesta_correcta' => 'A'
            ]);
        }
    }

    public function test_can_get_random_questions_without_correct_answer()
    {
        $response = $this->getJson("/api/public/unidades/{$this->unidad->id}/cuestionario");

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'preguntas')
                 ->assertJsonMissing(['respuesta_correcta']);
    }

    public function test_can_evaluate_responses()
    {
        $preguntas = Pregunta::all();
        
        $respuestas = [];
        foreach ($preguntas as $p) {
            $respuestas[$p->id] = 'A'; // Responder todas correctamente
        }

        $response = $this->postJson("/api/public/unidades/{$this->unidad->id}/cuestionario/evaluar", [
            'respuestas' => $respuestas
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('score', 10)
                 ->assertJsonPath('correct_count', 6);
    }

    public function test_enforces_limit_of_three_daily_attempts()
    {
        Cache::flush();

        // 1er intento
        $response = $this->postJson("/api/public/unidades/{$this->unidad->id}/cuestionario/evaluar", ['respuestas' => []]);
        $response->assertStatus(200);

        // 2do intento
        $response = $this->postJson("/api/public/unidades/{$this->unidad->id}/cuestionario/evaluar", ['respuestas' => []]);
        $response->assertStatus(200);

        // 3er intento
        $response = $this->postJson("/api/public/unidades/{$this->unidad->id}/cuestionario/evaluar", ['respuestas' => []]);
        $response->assertStatus(200);

        // 4to intento debe ser bloqueado
        $response = $this->postJson("/api/public/unidades/{$this->unidad->id}/cuestionario/evaluar", ['respuestas' => []]);
        $response->assertStatus(403)
                 ->assertJsonFragment(['message' => 'Has alcanzado el límite de 3 intentos diarios para esta unidad. No es posible enviar más respuestas hoy.']);
    }
}
