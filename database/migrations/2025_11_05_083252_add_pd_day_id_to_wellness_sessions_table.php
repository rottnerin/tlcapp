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
            $table->foreignId('p_d_day_id')->nullable()->constrained('p_d_days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wellness_sessions', function (Blueprint $table) {
            $table->dropForeign(['p_d_day_id']);
            $table->dropColumn('p_d_day_id');
        });
    }
};
