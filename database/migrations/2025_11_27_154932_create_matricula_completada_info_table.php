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
        Schema::create('matricula_completada_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("estudiante_id");
            $table->string("ip");
            $table->timestamps();
        });

        Schema::table('matricula_completada_info', function (Blueprint $table) {
            $table->foreign("estudiante_id")->references("id")->on("estudiantes")->onDelete("cascade");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matricula_completada_info');
    }
};
