<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('invitation_id')->nullable()->constrained()->onDelete('set null');
            $table->string('stripe_payment_intent_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 10, 2); // 10% para ti
            $table->decimal('organizer_amount', 10, 2); // 90% para organizador
            $table->enum('status', ['pending', 'succeeded', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};