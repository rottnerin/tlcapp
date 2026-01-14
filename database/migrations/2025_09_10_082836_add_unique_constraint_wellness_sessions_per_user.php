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
        // MySQL doesn't support partial unique indexes with WHERE clauses.
        // Instead, we use a generated (virtual) column that is NULL when the constraint shouldn't apply,
        // and contains the user_id when it should. MySQL allows multiple NULLs in unique indexes.
        DB::statement("
            ALTER TABLE user_sessions 
            ADD COLUMN active_wellness_user_id BIGINT UNSIGNED 
            GENERATED ALWAYS AS (
                CASE WHEN wellness_session_id IS NOT NULL AND status != 'cancelled' THEN user_id ELSE NULL END
            ) VIRTUAL
        ");

        DB::statement('CREATE UNIQUE INDEX unique_user_active_wellness_session ON user_sessions (active_wellness_user_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_sessions', function (Blueprint $table) {
            $table->dropUnique('unique_user_active_wellness_session');
            $table->dropColumn('active_wellness_user_id');
        });
    }
};