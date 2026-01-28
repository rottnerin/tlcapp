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
        Schema::table('user_selected_sessions', function (Blueprint $table) {
            $table->index(['user_id', 'academic_year'], 'idx_user_academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_selected_sessions', function (Blueprint $table) {
            $table->dropIndex('idx_user_academic_year');
        });
    }
};
