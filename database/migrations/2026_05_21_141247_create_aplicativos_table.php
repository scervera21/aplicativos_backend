<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'pgsql';  // Indica la conexion que se debe usar en los archivos de migraciones

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('security.aplicativos', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'aplicativos_user_id'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicativos');
    }
};
