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
        Schema::create('public.detalles_aplicativos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aplicativo_id');
            $table->foreign('aplicativo_id')->references('id')->on('public.aplicativos')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('lenguajes_frontend');
            $table->string('lenguajes_backend');
            $table->string('bases_de_datos');
            $table->string('observaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public.detalles_aplicativos');
    }
};
