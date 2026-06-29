<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GeneracionController;
use App\Http\Controllers\Api\GrupoController;
use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\ContenidoController;
use App\Http\Controllers\Api\PreguntaController;
use App\Http\Controllers\Api\CuestionarioController;
use App\Http\Controllers\Api\EvaluacionController;
use App\Http\Controllers\Api\SesionController;
use Illuminate\Support\Facades\Route;

// Rutas Públicas Generales
Route::get('/public/cursos', [ContenidoController::class, 'getCursos']);
Route::get('/public/unidades/{unidad}/contenido', [ContenidoController::class, 'getUnidadContenido']);
Route::get('/public/unidades/{unidad}/cuestionario', [CuestionarioController::class, 'getCuestionario']);
Route::post('/public/unidades/{unidad}/cuestionario/evaluar', [CuestionarioController::class, 'evaluarCuestionario']);
Route::get('/public/grupos/{grupo}', [GrupoController::class, 'showPublic']);

// Rate Limiting para Autenticación (10 peticiones/min)
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Rate Limiting para Autoinscripción e Inicio de Sesión (30 peticiones/min)
Route::middleware('throttle:30,1')->group(function () {
    Route::post('/public/grupos/{grupo}/inscribirse', [AlumnoController::class, 'inscribirPorQR']);
    Route::get('/public/sesiones/buscar/{codigo}', [SesionController::class, 'buscar']);
    Route::post('/public/sesiones/iniciar', [SesionController::class, 'iniciar']);
});

// Rate Limiting para Resolver Exámenes (120 peticiones/min para evitar pérdidas de progreso)
Route::middleware('throttle:120,1')->group(function () {
    Route::post('/public/intentos/{intento}/respuesta', [SesionController::class, 'guardarRespuesta']);
    Route::post('/public/intentos/{intento}/finalizar', [SesionController::class, 'finalizar']);
});

// Rutas Protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // CRUD Académico
    Route::apiResource('generaciones', GeneracionController::class);
    Route::apiResource('grupos', GrupoController::class);
    Route::apiResource('alumnos', AlumnoController::class);
    Route::apiResource('preguntas', PreguntaController::class);
    Route::apiResource('evaluaciones', EvaluacionController::class)->parameters(['evaluaciones' => 'evaluacion']);
    Route::apiResource('sesiones', SesionController::class)->parameters(['sesiones' => 'sesion']);
    Route::post('sesiones/{sesion}/toggle', [SesionController::class, 'toggle']);
    Route::get('sesiones/{sesion}/intentos', [SesionController::class, 'obtenerIntentos']);
    Route::get('sesiones/{sesion}/intentos/{intento}', [SesionController::class, 'obtenerIntentoDetalle']);
    Route::post('intentos/{intento}/calificar', [SesionController::class, 'guardarCalificacion']);
    Route::get('sesiones/{sesion}/estadisticas', [SesionController::class, 'obtenerEstadisticas']);
    Route::get('sesiones/{sesion}/exportar', [SesionController::class, 'exportarCSV']);

    // Importación masiva de alumnos
    Route::post('grupos/{grupo}/importar-alumnos', [AlumnoController::class, 'importarCSV']);
});
