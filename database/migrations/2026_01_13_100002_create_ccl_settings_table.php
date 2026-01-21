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
        Schema::create('ccl_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->string('title')->default('Collaborative Community Learning Sessions');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('ccl_settings')->insert([
            'is_active' => false,
            'title' => 'Collaborative Community Learning Sessions',
            'description' => 'Professional learning sessions led by our own teachers sharing their expertise.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ccl_settings');
    }
};
