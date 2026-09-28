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
            $table->foreignId('aplicativo_id')->constrained('public.aplicativos')->onDelete('cascade');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('lenguajes_frontend', 30)->nullable()->default('Por definir');
            $table->string('lenguajes_backend', 30)->nullable()->default('Por definir');
            $table->string('bases_de_datos', 30)->nullable()->default('Por definir');
            $table->string('observaciones', 100)->nullable();
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
        Schema::dropIfExists('public.detalles_aplicativos');
    }
};
