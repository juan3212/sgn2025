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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('usuarios')->onDelete('cascade'); // Clave foránea
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade'); // Clave foránea
            $table->float('valor');
            $table->timestamps();
        });

        Schema::table('notas', function (Blueprint $table) {
            $table->index('actividad_id');
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
