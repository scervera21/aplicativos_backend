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
            $table->foreignId('responsable_id')->constrained('security.users')->onDelete('cascade');
            $table->boolean('pap');
            $table->string('estatus',20);

            $table->integer('avance');  // % de avance (campo calculado)
            $table->integer('pendiente');   // % de pendiente (campo calculado)

            $table->foreignId('gerencia_linea_id')->constrained('public.gerencia_linea')->onDelete('cascade');
            $table->foreignId('detalles_id')->constrained('public.detalles_aplicativos')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropForeign(['detalles_id']);
        Schema::dropColumn('detalles_id');
        Schema::dropForeign(['gerencia_linea_id']);
        Schema::dropColumn('gerencia_linea_id');
        Schema::dropForeign(['responsable_id']);
        Schema::dropColumn('responsable_id');
        Schema::dropIfExists('public.aplicativos');
    }
};
