<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('apellido_paterno', 100)->nullable()->after('nombre');
            $table->string('apellido_materno', 100)->nullable()->after('apellido_paterno');
            $table->integer('edad')->nullable()->after('apellido_materno');
            $table->string('numero_celular', 20)->nullable()->after('edad');
        });

        // Copiar datos existentes de 'apellido' a 'apellido_paterno'
        DB::table('alumnos')->update([
            'apellido_paterno' => DB::raw('apellido')
        ]);

        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn('apellido');
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('apellido', 100)->nullable()->after('nombre');
        });

        DB::table('alumnos')->update([
            'apellido' => DB::raw('apellido_paterno')
        ]);

        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn('apellido_paterno');
            $table->dropColumn('apellido_materno');
            $table->dropColumn('edad');
            $table->dropColumn('numero_celular');
        });
    }
};
