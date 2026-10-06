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
            $table->foreignId('responsable')->constrained('security.users')->onUpdate('cascade')->onDelete('set null');
            $table->boolean('pap');
            $table->string('estatus',20)->default('Sin Estatus');
            $table->string('descripcion',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropForeign(['responsable']);
        Schema::dropColumn('responsable');
        Schema::dropIfExists('public.aplicativos');
    }
};
