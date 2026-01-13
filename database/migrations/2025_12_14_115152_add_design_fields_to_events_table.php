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
        Schema::table('events', function (Blueprint $table) {
            $table->string('template_id')->default('basic')->after('status');
            $table->string('primary_color')->default('#667eea')->after('template_id');
            $table->string('secondary_color')->default('#764ba2')->after('primary_color');
            $table->string('background_color')->default('#ffffff')->after('secondary_color');
            $table->string('font_family')->default('Inter')->after('background_color');
            $table->enum('font_size', ['small', 'medium', 'large'])->default('medium')->after('font_family');
            $table->json('design_elements')->nullable()->after('font_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'template_id',
                'primary_color',
                'secondary_color',
                'background_color',
                'font_family',
                'font_size',
                'design_elements'
            ]);
        });
    }
};