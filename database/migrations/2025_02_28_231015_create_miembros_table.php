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
        Schema::create('miembros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_miembro');
            $table->unsignedBigInteger('tipo_documento_id');
            $table->string('numero_documento')->unique();
            $table->integer('edad')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('direccion')->nullable();
            $table->text('informacion')->nullable();
            $table->unsignedBigInteger('cargo_id');
            $table->date('inicio_periodo');
            $table->date('final_periodo');
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('tipo_documento_id')->references('id')->on('tipo_documentos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('cargo_id')->references('id')->on('cargos')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros');
    }
};
