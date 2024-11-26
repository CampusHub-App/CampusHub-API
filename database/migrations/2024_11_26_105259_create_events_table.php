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
        Schema::create('events', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->foreignId('kategori_id')->constrained('categories')->onDelete('cascade');
            $table->string('user_id', 6);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('judul');
            $table->dateTime('datetime');
            $table->string('foto_event', 500)->nullable();
            $table->string('foto_pembicara', 500)->nullable();
            $table->string('pembicara');
            $table->string('role');
            $table->string('deskripsi', 3000);
            $table->integer('available_slot');
            $table->boolean('is_offline')->default(false);
            $table->string('tempat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};