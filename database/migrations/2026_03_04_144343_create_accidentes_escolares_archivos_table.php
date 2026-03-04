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

        Schema::create('accidentes_escolares_archivos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registro_accidente_id');
            $table->foreign('registro_accidente_id')->references('id')->on('registro_accidente_usuario');
            $table->bigInteger('usuario_id');
            $table->string('nombre_archivo')->default('Acta notificacion accidentes');
            $table->string('path');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accidentes_escolares_archivos');
    }
};
