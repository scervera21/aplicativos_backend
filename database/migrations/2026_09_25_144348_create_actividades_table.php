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
        Schema::create('public.actividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_actividad', 150);
            $table->date('fecha_creacion');
            $table->date('fecha_culminacion')->nullable();

            $table->enum('prioridad', ['Baja','Media','Alta']);

            $table->foreignId('area_id')->constrained('security.areas')->onDelete('cascade');

            $table->enum('estado', ['Pendiente','Finalizado','Atrasado'])->default('Pendiente');
            $table->boolean('completado')->default(false);
            $table->text('comentarios')->nullable();
            $table->foreignId('aplicativo_id')->constrained('public.aplicativos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropForeign(['aplicativo_id', 'area_id']);
        Schema::dropColumn(['aplicativo_id', 'area_id']);
        Schema::dropIfExists('public.actividades');
    }
};
