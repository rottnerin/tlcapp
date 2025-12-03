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
        Schema::table('schedule_items', function (Blueprint $table) {
            $table->foreignId('wellness_session_id')->nullable()->constrained('wellness_sessions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_items', function (Blueprint $table) {
            $table->dropForeign(['wellness_session_id']);
            $table->dropColumn('wellness_session_id');
        });
    }
};
