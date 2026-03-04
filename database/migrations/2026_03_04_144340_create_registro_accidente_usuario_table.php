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

        Schema::create('registro_accidente_usuario', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('datos_usuario_enfermeria_id');
            $table->foreign('datos_usuario_enfermeria_id')->references('id')->on('asistencia_enfermeria_datos_usuario');
            $table->string('acudiente');
            $table->string('parentesco');
            $table->string('eps');
            $table->boolean('seguridad_social');
            $table->string('quien_atiende');
            $table->dateTime('fecha_accidente');
            $table->boolean('uso_sustancias');
            $table->string('lugar_accidente');
            $table->string('numero_ruta')->nullable();
            $table->string('lugar_atencion');
            $table->string('actividad_realizada');
            $table->json('mecanismo');
            $table->json('naturaleza_lesion');
            $table->json('parte_afectada');
            $table->text('descripcion');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_accidente_usuario');
    }
};
