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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('usuarios');
            $table->foreignId('materia_id')->constrained('materias');
            $table->foreignId('periodo_id')->constrained('periodos'); // Clave foránea
            $table->bigInteger('competencia_id');
            $table->foreignId('tipo_nota_id')->constrained('tipos_notas'); // Clave foránea
            $table->string('descripcion', 500);
            $table->float('valor');
            $table->timestamps();
        });

        Schema::table('notas', function (Blueprint $table) {
            $table->index('estudiante_id');
            $table->index('materia_id');
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
