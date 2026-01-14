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
        Schema::table('p_d_days', function (Blueprint $table) {
            $table->enum('season', ['fall', 'spring'])->nullable()->after('is_active');
            $table->string('academic_year', 9)->nullable()->after('season'); // Format: 2025-2026
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_d_days', function (Blueprint $table) {
            $table->dropColumn(['season', 'academic_year']);
        });
    }
};
