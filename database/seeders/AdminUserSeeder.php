<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Admin email addresses - update these with actual AES admin emails
        $adminEmails = [
            'mhaque@aes.ac.in',      // Your actual email from Google OAuth
            'booking@aes.ac.in',
            'rmckinnie@aes.ac.in',
            'admin@aes.ac.in',
            'principal@aes.ac.in',
            'pld.coordinator@aes.ac.in',
        ];

        foreach ($adminEmails as $email) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'AES Administrator',
                    'email' => $email,
                    'password' => Hash::make('admin123'), // Fallback password (won't be used with Google OAuth)
                    'email_verified_at' => now(),
                    'is_admin' => true,
                    'is_active' => true,
                    'division_id' => Division::where('name', 'HS')->first()?->id, // Default to HS
                ]
            );

            // Update existing users to admin if they already exist
            if (!$user->is_admin) {
                $user->update(['is_admin' => true]);
                $this->command->info("Made {$email} an administrator");
            } else {
                $this->command->info("Administrator {$email} already exists");
            }
        }
    }
}
