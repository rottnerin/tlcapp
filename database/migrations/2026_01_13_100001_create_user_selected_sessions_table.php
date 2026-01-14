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
        Schema::create('user_selected_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('selectable'); // selectable_type, selectable_id
            $table->string('academic_year', 9)->nullable(); // Format: 2025-2026
            $table->timestamps();

            // Ensure a user can't select the same session twice
            $table->unique(['user_id', 'selectable_type', 'selectable_id'], 'unique_user_selection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_selected_sessions');
    }
};
