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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->unsignedBigInteger('tipo_nota');
            $table->unsignedBigInteger('materia_id');
            $table->unsignedBigInteger('competencia_id');
            $table->unsignedBigInteger('periodo_id');
            $table->timestamps();
            $table->foreign('competencia_id')->references('id')->on('competencias')->onDelete('cascade');
            $table->foreign('periodo_id')->references('id')->on('periodos')->onDelete('cascade');
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
            $table->foreign('tipo_nota')->references('id')->on('tipos_notas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
