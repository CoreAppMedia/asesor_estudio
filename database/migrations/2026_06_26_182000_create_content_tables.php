<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contenidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subtema_id')->nullable()->constrained('cat_subtemas')->onDelete('cascade');
            $table->foreignId('tema_id')->nullable()->constrained('cat_temas')->onDelete('cascade');
            $table->string('tipo'); // e.g., 'teoria', 'leccion'
            $table->string('json_path');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contenido_id')->constrained('contenidos')->onDelete('cascade');
            $table->string('tipo'); // e.g., 'pdf', 'video', 'imagen'
            $table->string('url');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('ejemplos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contenido_id')->constrained('contenidos')->onDelete('cascade');
            $table->string('titulo');
            $table->text('explicacion');
            $table->text('solucion');
            $table->timestamps();
        });

        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contenido_id')->constrained('contenidos')->onDelete('cascade');
            $table->text('instruccion');
            $table->text('respuesta_sugerida');
            $table->timestamps();
        });

        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidad_id')->constrained('cat_unidades')->onDelete('cascade');
            $table->text('texto_pregunta');
            $table->string('tipo_pregunta'); // e.g., 'opcion_multiple', 'respuesta_abierta'
            $table->json('opciones_json')->nullable();
            $table->string('respuesta_correcta');
            $table->timestamps();
        });

        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('unidad_id')->constrained('cat_unidades')->onDelete('cascade');
            $table->integer('total_preguntas');
            $table->integer('tiempo_limite_minutos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
        Schema::dropIfExists('preguntas');
        Schema::dropIfExists('ejercicios');
        Schema::dropIfExists('ejemplos');
        Schema::dropIfExists('recursos');
        Schema::dropIfExists('contenidos');
    }
};
