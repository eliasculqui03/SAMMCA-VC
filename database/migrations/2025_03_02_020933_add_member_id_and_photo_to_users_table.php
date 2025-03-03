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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('miembro_id')->nullable();
            $table->string('foto')->nullable();

            // Agregar foreign key si es necesario
            $table->foreign('miembro_id')->references('id')->on('miembros')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['miembro_id']);
            $table->dropColumn('miembro_id');
            $table->dropColumn('foto');
        });
    }
};
