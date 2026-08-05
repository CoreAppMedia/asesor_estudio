<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->foreignId('unidad_id')->nullable()->change();
            $table->foreignId('semestre_id')->nullable()->after('unidad_id')->constrained('cat_semestres')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->dropForeign(['semestre_id']);
            $table->dropColumn('semestre_id');
            // Nota: revertir a no nullable. En SQLite puede requerir precaución si hay datos nulos.
            $table->foreignId('unidad_id')->nullable(false)->change();
        });
    }
};
