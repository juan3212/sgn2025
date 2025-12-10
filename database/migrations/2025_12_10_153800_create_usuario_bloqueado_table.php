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
        Schema::create('usuario_bloqueado', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("usuario_id");
            $table->string("servicio");
            $table->timestamps();
        });
        Schema::table('usuario_bloqueado', function (Blueprint $table) {
            $table->foreign("usuario_id")->references("id")->on("usuarios")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_bloqueado');
    }
};
