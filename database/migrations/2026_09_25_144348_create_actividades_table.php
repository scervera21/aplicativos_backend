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
            $table->string('actividad', 100);
            $table->date('creado_el')->default(now());
            $table->date('culminado_el')->nullable();

            $table->enum('area', ['Levantamiento de información','Base de datos','Desarrollo','Infraestructura']);
            $table->enum('prioridad', ['Baja','Media','Alta']);

            $table->boolean('completado');

            $table->text('comentarios')->nullable();
            $table->foreignId('aplicativo_id')->constrained('public.aplicativos')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropForeign(['aplicativo_id']);
        Schema::dropColumn('aplicativo_id');
        Schema::dropIfExists('public.actividades');
    }
};
