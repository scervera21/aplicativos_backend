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
        Schema::create('public.colaboradores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gerencia_id')->constrained('public.gerencias')->onDelete('cascade');
            $table->foreignId('actividad_id')->constrained('public.actividades')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public.colaboradores');
    }
};
