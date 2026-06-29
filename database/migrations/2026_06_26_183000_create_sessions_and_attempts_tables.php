<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('evaluacion_id')->constrained('evaluaciones')->onDelete('cascade');
            $table->string('codigo_acceso')->unique();
            $table->boolean('activa')->default(true);
            $table->timestamp('fecha_cierre')->nullable();
            $table->timestamps();
        });

        Schema::create('intentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('sesion_id')->constrained('sesiones')->onDelete('cascade');
            $table->integer('numero_intento')->default(1);
            $table->timestamp('iniciado_at')->useCurrent();
            $table->timestamp('finalizado_at')->nullable();
            $table->timestamps();
        });

        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intento_id')->constrained('intentos')->onDelete('cascade');
            $table->foreignId('pregunta_id')->constrained('preguntas')->onDelete('cascade');
            $table->text('respuesta_alumno');
            $table->boolean('es_correcta')->nullable();
            $table->decimal('puntaje', 5, 2)->nullable();
            $table->text('feedback_profesor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultados');
        Schema::dropIfExists('intentos');
        Schema::dropIfExists('sesiones');
    }
};
