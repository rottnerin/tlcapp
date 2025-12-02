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
        Schema::table('wellness_sessions', function (Blueprint $table) {
            // Add co-presenter fields
            $table->string('co_presenter_name')->nullable()->after('presenter_email');
            $table->string('co_presenter_email')->nullable()->after('co_presenter_name');
            
            // Modify category to store multiple values (JSON)
            $table->json('category')->nullable()->change();
            
            // Remove start_time and end_time columns since they're fixed
            $table->dropColumn(['start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wellness_sessions', function (Blueprint $table) {
            // Remove co-presenter fields
            $table->dropColumn(['co_presenter_name', 'co_presenter_email']);
            
            // Restore category as string
            $table->string('category')->nullable()->change();
            
            // Add back start_time and end_time columns
            $table->datetime('start_time');
            $table->datetime('end_time');
        });
    }
};