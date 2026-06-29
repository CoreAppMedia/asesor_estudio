<?php

namespace Tests\Feature\Api;

use App\Models\Alumno;
use App\Models\Generacion;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AcademicTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    public function test_can_list_generaciones()
    {
        Generacion::create(['anio_inicio' => 2026, 'anio_fin' => 2027]);

        $response = $this->withHeaders($this->getHeaders())
                         ->getJson('/api/generaciones');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function test_can_create_grupo()
    {
        $gen = Generacion::create(['anio_inicio' => 2026, 'anio_fin' => 2027]);

        $response = $this->withHeaders($this->getHeaders())
                         ->postJson('/api/grupos', [
                             'generacion_id' => $gen->id,
                             'nombre' => 'Grupo 401',
                         ]);

        $response->assertStatus(201)
                 ->assertJsonPath('nombre', 'Grupo 401');
    }

    public function test_can_import_students_from_csv()
    {
        $gen = Generacion::create(['anio_inicio' => 2026, 'anio_fin' => 2027]);
        $grupo = Grupo::create(['generacion_id' => $gen->id, 'nombre' => 'Grupo 401']);

        // Crear CSV de prueba en memoria
        $csvContent = "numero_lista,nombre,apellido\n1,Juan,Perez\n2,Maria,Gomez\n3,Pedro,Rodriguez\n";
        $file = UploadedFile::fake()->createWithContent('alumnos.csv', $csvContent);

        $response = $this->withHeaders($this->getHeaders())
                         ->postJson("/api/grupos/{$grupo->id}/importar-alumnos", [
                             'file' => $file,
                         ]);

        $response->assertStatus(200)
                 ->assertJsonPath('imported_count', 3);

        $this->assertDatabaseHas('alumnos', [
            'grupo_id' => $grupo->id,
            'numero_lista' => 1,
            'nombre' => 'Juan',
            'apellido_paterno' => 'Perez',
        ]);
    }
}
