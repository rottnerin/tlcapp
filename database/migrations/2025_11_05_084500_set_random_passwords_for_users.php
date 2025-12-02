<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing users with empty or null passwords to have a random 10-digit code
        $users = DB::table('users')->whereNull('password')->orWhere('password', '')->get();
        
        foreach ($users as $user) {
            $randomPassword = Str::random(10);
            DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => Hash::make($randomPassword)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this
    }
};
