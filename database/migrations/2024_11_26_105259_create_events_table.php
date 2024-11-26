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
            $table->id();
            $table->foreignId('kategori_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('tipe_id')->constrained('types')->onDelete('cascade');
            $table->string('judul');
            $table->dateTime('datetime');
            $table->string('foto_event', 500)->nullable();
            $table->string('foto_pembicara',500)->nullable();
            $table->string('pembicara')->nullable();
            $table->string('role')->nullable();
            $table->string('deskripsi', 3000)->nullable();
            $table->integer('available_slot');
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
