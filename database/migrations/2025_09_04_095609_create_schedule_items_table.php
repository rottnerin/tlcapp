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
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->date('date');
            $table->string('presenter_primary')->nullable();
            $table->string('presenter_secondary')->nullable();
            $table->text('presenter_bio')->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('current_enrollment')->default(0);
            $table->text('equipment_needed')->nullable();
            $table->text('special_requirements')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('session_type')->default('regular'); // regular, keynote, break, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_items');
    }
};
