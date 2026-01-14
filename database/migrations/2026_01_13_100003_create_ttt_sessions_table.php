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
        Schema::create('ttt_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('presenter_name');
            $table->string('presenter_email')->nullable();
            $table->text('presenter_bio')->nullable();
            $table->string('co_presenter_name')->nullable();
            $table->string('co_presenter_email')->nullable();
            $table->string('location')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('contact_hours', 4, 2)->nullable(); // For transcript calculation
            $table->foreignId('p_d_day_id')->nullable()->constrained('p_d_days')->onDelete('set null');
            $table->foreignId('division_id')->nullable()->constrained('divisions')->onDelete('set null');
            $table->json('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ttt_sessions');
    }
};
