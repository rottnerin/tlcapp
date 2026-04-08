<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('earth_day_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('earth_day_workshop_id')->constrained()->cascadeOnDelete();
            $table->datetime('enrolled_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'earth_day_workshop_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('earth_day_enrollments');
    }
};
