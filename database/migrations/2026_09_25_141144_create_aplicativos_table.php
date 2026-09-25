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
        Schema::create('public.aplicativos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_aplicativo');
            $table->string('abreviacion');
            $table->string('descripcion');
            $table->foreignId('responsable_id')->constrained('security.users')->onDelete('cascade');
            $table->boolean('pap');
            $table->string('estatus');
            $table->integer('avance');
            $table->foreignId('gerencia_general_id')->constrained('public.gerencia_general')->onDelete('cascade');
            $table->unsignedBigInteger('gerencia_linea_id');
            $table->foreign('gerencia_linea_id')->references('id')->on('public.gerencia_linea')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public.aplicativos');
    }
};
