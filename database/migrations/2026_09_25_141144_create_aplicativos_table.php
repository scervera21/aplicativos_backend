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
            $table->string('nombre_aplicativo',30);
            $table->string('abreviacion',6);
            $table->string('descripcion',100)->nullable();
            $table->foreignId('responsable')->constrained('security.users')->onDelete('cascade');
            $table->boolean('pap');
            $table->string('estatus',20);
            $table->foreignId('gerencia_linea')->constrained('public.gerencias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropForeign(['gerencia_linea']);
        Schema::dropColumn('gerencia_linea');
        Schema::dropForeign(['responsable']);
        Schema::dropColumn('responsable');
        Schema::dropIfExists('public.aplicativos');
    }
};
