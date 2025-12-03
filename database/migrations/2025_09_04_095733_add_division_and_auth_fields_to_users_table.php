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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('division_id')->nullable()->constrained()->onDelete('set null');
            $table->string('google_id')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->datetime('last_login_at')->nullable();
            $table->json('preferences')->nullable(); // User preferences as JSON
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn([
                'division_id', 
                'google_id', 
                'avatar', 
                'is_admin', 
                'last_login_at', 
                'preferences', 
                'is_active'
            ]);
        });
    }
};
