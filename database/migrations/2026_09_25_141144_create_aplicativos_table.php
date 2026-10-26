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
            $table->string('nombre_aplicativo',80)->unique();
            $table->string('alias',8)->nullable();
            $table->boolean('pap')->default(false);
            $table->string('estatus',30)->default('Sin Estatus');
            $table->string('descripcion',200)->nullable();
            $table->string('responsable',50)->nullable();
            $table->string('lenguajes_frontend', 50)->nullable();
            $table->string('lenguajes_backend', 50)->nullable();
            $table->string('bases_datos', 50)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('gerencia_id')->nullable()->constrained('public.gerencias')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public.aplicativos', function (Blueprint $table) {
            $table->dropForeign(['gerencia_id']);
            $table->dropColumn('gerencia_id');
        });
        Schema::dropIfExists('public.aplicativos');
    }
};
