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
            $table->string('aplicativo',80);
            $table->string('alias',6)->nullable();
            $table->boolean('pap');
            $table->string('estatus',20)->default('Sin Estatus');
            $table->string('descripcion',100)->nullable();
            $table->foreignId('responsable')->nullable()->constrained('security.users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('gerencia_id')->nullable()->constrained('public.gerencias')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropForeign(['responsable']);
        Schema::dropForeign(['gerencia_id']);
        Schema::dropColumn('responsable');
        Schema::dropColumn('gerencia_id');
        Schema::dropIfExists('public.aplicativos');
    }
};
