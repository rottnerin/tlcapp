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
        Schema::create('wellness_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('presenter_name')->nullable();
            $table->text('presenter_bio')->nullable();
            $table->string('presenter_email')->nullable();
            $table->string('location')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->date('date');
            $table->integer('max_participants')->default(20);
            $table->integer('current_enrollment')->default(0);
            $table->string('category')->nullable(); // yoga, meditation, fitness, etc.
            $table->text('equipment_needed')->nullable();
            $table->text('special_requirements')->nullable();
            $table->text('preparation_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_waitlist')->default(true);
            $table->string('source')->default('manual'); // manual, google_form
            $table->string('external_id')->nullable(); // for Google Forms integration
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wellness_sessions');
    }
};
