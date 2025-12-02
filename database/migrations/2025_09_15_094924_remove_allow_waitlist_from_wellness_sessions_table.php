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
            $table->dropColumn('allow_waitlist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wellness_sessions', function (Blueprint $table) {
            $table->boolean('allow_waitlist')->default(true);
        });
    }
};