<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // protected $connection = 'security';
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('security.users', function (Blueprint $table) {
        //     $table->foreignId('gerencia_id')->constrained('public.gerencias')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropForeign(['gerencia_id']);
        Schema::dropColumn('gerencia_id');
        Schema::dropIfExists('security.users');
    }
};
