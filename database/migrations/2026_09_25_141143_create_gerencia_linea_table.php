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
        Schema::create('public.gerencia_linea', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_linea',30);
            $table->string('abreviacion',5);
            $table->string('ubicacion',10);
            $table->foreignId('gerencia_general_id')->constrained('public.gerencia_general')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropForeign(['gerencia_general_id']);
        Schema::dropColumn('gerencia_general_id');
        Schema::dropIfExists('public.gerencia_linea');
    }
};
