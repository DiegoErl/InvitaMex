<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('host_name');
            $table->string('location');
            $table->date('event_date');
            $table->time('event_time');
            $table->string('event_image')->nullable();
            $table->enum('type', ['boda', 'cumpleanos', 'graduacion', 'corporativo', 'social', 'religioso', 'otro']);
            $table->enum('payment_type', ['gratis', 'pago']);
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description');
            $table->integer('max_attendees')->nullable();
            $table->boolean('is_public')->default(true);
            $table->enum('status', ['borrador', 'publicado', 'cancelado'])->default('borrador');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};