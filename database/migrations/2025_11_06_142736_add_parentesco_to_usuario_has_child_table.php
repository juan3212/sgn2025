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
        Schema::table('usuario_has_child', function (Blueprint $table) {
            //
            $table->string('parentesco')->after('child_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario_has_child', function (Blueprint $table) {
            //
            $table->dropColumn('parentesco');
        });
    }
};
