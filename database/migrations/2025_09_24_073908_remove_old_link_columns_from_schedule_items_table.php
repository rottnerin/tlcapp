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
            $table->dropColumn(['link_title', 'link_url', 'link_description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_items', function (Blueprint $table) {
            $table->string('link_title')->nullable()->after('special_requirements');
            $table->string('link_url')->nullable()->after('link_title');
            $table->text('link_description')->nullable()->after('link_url');
        });
    }
};