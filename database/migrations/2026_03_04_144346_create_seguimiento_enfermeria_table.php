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

        Schema::create('seguimiento_enfermeria', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('datos_usuario_enfermeria_id');
            $table->foreign('datos_usuario_enfermeria_id')->references('id')->on('asistencia_enfermeria_datos_usuario');
            $table->bigInteger('numero_dia');
            $table->date('fecha');
            $table->string('responsable');
            $table->text('observaciones');
            $table->text('observaciones_finales')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento');
    }
};
