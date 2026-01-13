<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Modificar el campo status para incluir 'rechazado'
        Schema::table('invitations', function (Blueprint $table) {
            $table->enum('status', ['pendiente', 'confirmado', 'rechazado', 'usado', 'cancelado'])
                  ->default('pendiente')
                  ->change();
        });

        // Agregar campo deadline a events
        Schema::table('events', function (Blueprint $table) {
            $table->timestamp('rsvp_deadline')->nullable()->after('event_time');
        });
    }

    public function down()
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->enum('status', ['pendiente', 'confirmado', 'usado', 'cancelado'])
                  ->default('pendiente')
                  ->change();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('rsvp_deadline');
        });
    }
};