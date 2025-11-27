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
        Schema::create('usuario_facturacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('acudiente_id');
            $table->timestamps();
        });

        Schema::table('usuario_facturacion', function (Blueprint $table) {
            $table->foreign('estudiante_id')->references('id')->on('usuarios');
            $table->foreign('acudiente_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_facturacion');
    }
};
