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
        Schema::create('pl_wednesday_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pl_wednesday_session_id')->constrained('pl_wednesday_sessions')->onDelete('cascade');
            $table->string('title');
            $table->string('url');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pl_wednesday_links');
    }
};
