<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add a partial unique index to ensure a user can only have one active wellness session enrollment
        // This uses a raw SQL statement since Laravel doesn't have built-in support for partial unique indexes
        DB::statement('CREATE UNIQUE INDEX unique_user_active_wellness_session ON user_sessions (user_id) WHERE wellness_session_id IS NOT NULL AND status != "cancelled"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS unique_user_active_wellness_session');
    }
};