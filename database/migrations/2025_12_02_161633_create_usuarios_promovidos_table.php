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
        Schema::create('usuarios_promovidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("usuario_id");
            $table->unsignedBigInteger("grado_actual");
            $table->unsignedBigInteger("grado_destino");
            $table->timestamps();
        });
        Schema::table("usuarios_promovidos", function (Blueprint $table) {
            $table->foreign("usuario_id")->references("id")->on("usuarios")->onDelete("cascade");
            $table->foreign("grado_actual")->references("id")->on("grados")->onDelete("cascade");
            $table->foreign("grado_destino")->references("id")->on("grados")->onDelete("cascade");
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_promovidos');
    }
};
