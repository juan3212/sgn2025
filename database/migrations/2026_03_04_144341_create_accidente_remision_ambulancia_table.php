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

        Schema::create('accidente_remision_ambulancia', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registro_accidente_id');
            $table->foreign('registro_accidente_id')->references('id')->on('registro_accidente_usuario');
            $table->dateTime('hora_de_llamada');
            $table->string('quien_atiende');
            $table->text('instrucciones_recibidas');
            $table->string('numero_movil');
            $table->dateTime('hora_llegada_movil');
            $table->string('entidad_remitida');
            $table->string('canal_atencion');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accidente_remision_ambulancia');
    }
};
