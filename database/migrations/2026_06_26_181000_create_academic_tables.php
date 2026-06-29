<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generaciones', function (Blueprint $table) {
            $table->id();
            $table->integer('anio_inicio');
            $table->integer('anio_fin');
            $table->timestamps();
        });

        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generacion_id')->constrained('generaciones')->onDelete('cascade');
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->integer('numero_lista');
            $table->string('nombre');
            $table->string('apellido');
            $table->timestamps();

            // Unicidad del número de lista en un grupo
            $table->unique(['grupo_id', 'numero_lista']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
        Schema::dropIfExists('grupos');
        Schema::dropIfExists('generaciones');
    }
};
