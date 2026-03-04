<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("usuario_retirado", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("usuario_id");
            $table->string("motivo_retiro");
            $table->timestamps();
        });

        Schema::table("usuario_retirado", function (Blueprint $table) {
            $table
                ->foreign("usuario_id")
                ->references("id")
                ->on("usuarios")
                ->onDelete("cascade");
            $table->unique("usuario_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("usuario_retirado");
    }
};
