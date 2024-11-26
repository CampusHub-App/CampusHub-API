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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->boolean('is_admin')->default(false);
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('photo', 500)->nullable();
            $table->string('nomor_telepon')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 500)->nullable();
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
