<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('cat_semestres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('cat_materias')->onDelete('cascade');
            $table->integer('numero');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('cat_unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semestre_id')->constrained('cat_semestres')->onDelete('cascade');
            $table->integer('numero');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('cat_temas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidad_id')->constrained('cat_unidades')->onDelete('cascade');
            $table->integer('numero');
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('cat_subtemas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tema_id')->constrained('cat_temas')->onDelete('cascade');
            $table->integer('numero');
            $table->string('nombre');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_subtemas');
        Schema::dropIfExists('cat_temas');
        Schema::dropIfExists('cat_unidades');
        Schema::dropIfExists('cat_semestres');
        Schema::dropIfExists('cat_materias');
    }
};
