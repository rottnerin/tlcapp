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
        Schema::create('ccl_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ccl_session_id')->constrained('ccl_sessions')->onDelete('cascade');
            $table->string('title');
            $table->string('url');
            $table->string('type')->default('resource'); // resource, video, document, etc.
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ccl_links');
    }
};
