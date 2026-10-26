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
            $table->enum('prioridad', ['BAJA','MEDIA','ALTA']);
            $table->foreignId('area_id')->constrained('security.areas')->onDelete('cascade');
            $table->enum('estado', ['PENDIENTE','CULMINADO','ATRASADO'])->default('PENDIENTE');
            $table->unsignedSmallInteger('porcentaje')->default(0); // Porcentaje de avance de la actividad, solo se permite de 0 a 100
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
        Schema::table('public.actividades', function (Blueprint $table) {
            $table->dropForeign(['aplicativo_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['aplicativo_id', 'area_id']);
        });
        Schema::dropIfExists('public.actividades');
    }
};
