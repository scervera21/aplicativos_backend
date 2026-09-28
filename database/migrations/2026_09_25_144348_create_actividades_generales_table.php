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
        Schema::create('public.actividades_generales', function (Blueprint $table) {
            $table->id();
            $table->string('actividad', 50);
            $table->date('creado_el');
            $table->date('culminado_el');

            $table->enum('area', ['Levantamiento de información','Base de datos','Desarrollo','Infraestructura']);
            $table->enum('prioridad', ['baja','media','alta']);

            // Un array json que almacena las gerencias de linea que participan en el aplicativo
            // Ejemplo: ["Gerencia de linea 1", "Gerencia de linea 2", "Gerencia de linea 3"] 
            $table->json('colaboradores')->nullable();

            $table->integer('porcentaje_avance');

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
        Schema::dropForeign(['aplicativo_id']);
        Schema::dropColumn('aplicativo_id');
        Schema::dropIfExists('public.actividades_generales');
    }
};
