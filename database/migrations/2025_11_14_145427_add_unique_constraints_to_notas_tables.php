<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
    {
        // Llave única para la tabla 'notas'
        Schema::table('notas', function (Blueprint $table) {
            $table->unique(['estudiante_id', 'actividad_id'], 'notas_estudiante_actividad_unique');
        });

        // Llave única para la tabla 'notas_finales_competencias'
        Schema::table('notas_finales_competencias', function (Blueprint $table) {
            $table->unique(['estudiante_id', 'materia_id', 'competencia_id'], 'notas_finales_estudiante_materia_competencia_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->dropUnique('notas_estudiante_actividad_unique');
        });

        Schema::table('notas_finales_competencias', function (Blueprint $table) {
            $table->dropUnique('notas_finales_estudiante_materia_competencia_unique');
        });
    }
};
