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
        Schema::create('notas_finales_competencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('materia_id');
            $table->unsignedBigInteger('competencia_id');
            $table->decimal('nota_final', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('estudiante_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
            $table->foreign('competencia_id')->references('id')->on('competencias')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_finales_competencias');
    }
};
