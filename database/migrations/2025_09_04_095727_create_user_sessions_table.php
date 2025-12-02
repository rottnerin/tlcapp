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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wellness_session_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('schedule_item_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->default('confirmed'); // confirmed, waitlisted, cancelled
            $table->datetime('enrolled_at');
            $table->text('notes')->nullable();
            $table->integer('rating')->nullable(); // 1-5 star rating
            $table->text('feedback')->nullable();
            $table->boolean('attended')->nullable();
            $table->timestamps();
            
            // Ensure user can only enroll in one session per time slot
            $table->index(['user_id', 'enrolled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
