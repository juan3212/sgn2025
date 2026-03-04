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
        Schema::disableForeignKeyConstraints();

        Schema::create('asistencia_enfermeria_consulta', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('datos_usuario_enfermeria_id');
            $table->foreign('datos_usuario_enfermeria_id')->references('id')->on('asistencia_enfermeria_datos_usuario');
            $table->dateTime('hora_ingreso');
            $table->text('motivo_consulta');
            $table->text('procedimiento');
            $table->json('accion_tomada');
            $table->dateTime('hora_accion');
            $table->boolean('seguimiento');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia_enfermeria_consulta');
    }
};
