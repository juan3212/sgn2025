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
        Schema::table('notas_finales_materias', function (Blueprint $table) {
            $table->unique(['estudiante_id', 'materia_id', 'periodo_id'], 'unique_notas_finales_materias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notas_finales_materias', function (Blueprint $table) {
            $table->dropUnique('unique_notas_finales_materias');
        });
    }
};
