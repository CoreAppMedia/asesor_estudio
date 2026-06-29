<?php

namespace Tests\Feature\Api;

use App\Models\Materia;
use App\Models\Semestre;
use App\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContenidoTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_cursos_catalog()
    {
        $materia = Materia::create([
            'nombre' => 'Matemáticas CCH',
            'descripcion' => 'Plan de estudios'
        ]);

        $response = $this->getJson('/api/public/cursos');

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Matemáticas CCH']);
    }

    public function test_can_get_unidad_content_placeholder_when_file_does_not_exist()
    {
        $materia = Materia::create([
            'nombre' => 'Matemáticas CCH',
            'descripcion' => 'Plan de estudios'
        ]);

        $semestre = Semestre::create([
            'materia_id' => $materia->id,
            'numero' => 1,
            'descripcion' => 'Matemáticas I'
        ]);

        $unidad = Unidad::create([
            'semestre_id' => $semestre->id,
            'numero' => 99, // Unidad inexistente en JSON
            'nombre' => 'Unidad Fantasma',
            'descripcion' => 'Unidad de prueba'
        ]);

        $response = $this->getJson("/api/public/unidades/{$unidad->id}/contenido");

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Unidad Fantasma'])
                 ->assertJsonFragment(['introduccion' => 'El material didáctico para esta unidad está siendo redactado y validado por el profesor titular de la materia.']);
    }

    public function test_can_get_unidad_content_from_json_when_file_exists()
    {
        $materia = Materia::create([
            'nombre' => 'Matemáticas CCH',
            'descripcion' => 'Plan de estudios'
        ]);

        $semestre = Semestre::create([
            'materia_id' => $materia->id,
            'numero' => 1,
            'descripcion' => 'Matemáticas I'
        ]);

        // La unidad 1 ya cuenta con el archivo JSON que creamos: matematicas_1_u1.json
        $unidad = Unidad::create([
            'semestre_id' => $semestre->id,
            'numero' => 1,
            'nombre' => 'Números y operaciones',
            'descripcion' => 'Unidad 1 descriptiva'
        ]);

        $response = $this->getJson("/api/public/unidades/{$unidad->id}/contenido");

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Números y operaciones'])
                 ->assertJsonStructure([
                     'id',
                     'tipo',
                     'unidad' => ['id', 'numero', 'nombre', 'descripcion'],
                     'contenido' => [
                         'introduccion',
                         'objetivos',
                         'conocimientos_previos',
                         'explicacion',
                         'conceptos_clave',
                         'ejemplos',
                         'errores_comunes',
                         'ejercicios_guiados',
                         'resumen',
                         'bibliografia'
                     ]
                 ]);
    }
}
