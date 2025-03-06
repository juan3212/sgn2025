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
        //
        Schema::create('materia_has_competencia', function (Blueprint $table) {
            $table->unsignedBigInteger('materia_id');
            $table->unsignedBigInteger('competencia_id');
            $table->timestamps();
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
            $table->foreign('competencia_id')->references('id')->on('competencias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('materia_has_competencia');
    }
};
