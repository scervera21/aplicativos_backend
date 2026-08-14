<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'security';
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username',20)->unique()->index();
            $table->string('email')->unique();
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->string('password');
            $table->boolean('status')->default(true);   // Indica si el usuario está activo o inactivo
            $table->unsignedSmallInteger('failed_login_attempts')->default(0); // Contador de intentos fallidos
            $table->timestamp('locked_until')->nullable(); // Bloquea la cuenta del usuario hasta que el usuario ingrese el token de recuperación
            // $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
