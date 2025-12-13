<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->integer('order')->default(1); // Orden de la imagen en la galería
            $table->timestamps();
            
            // Índice para mejorar consultas
            $table->index(['event_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_images');
    }
};