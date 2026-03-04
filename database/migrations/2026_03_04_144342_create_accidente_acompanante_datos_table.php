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

        Schema::create('accidente_acompanante_datos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registro_accidente_id');
            $table->foreign('registro_accidente_id')->references('id')->on('registro_accidente_usuario');
            $table->string('nombre');
            $table->bigInteger('numero_identificacion');
            $table->string('vinculo_con_estudiante');
            $table->string('telefono');
            $table->dateTime('hora');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accidente_acompanante_datos');
    }
};
