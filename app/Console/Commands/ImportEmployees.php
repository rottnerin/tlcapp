<?php

namespace App\Console\Commands;

use App\Models\Division;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportEmployees extends Command
{
    protected $signature = 'users:import-employees
                            {--file= : Path to employee CSV (default: public/employee_details.csv)}
                            {--dry-run : Parse CSV and show what would be imported without making changes}';

    protected $description = 'Clear all users and import from employee_details.csv. Maps Business Unit: School Wide→ALL, Elementary School→ES, Middle School→MS, High School→HS, else→NTS.';

    private array $divisionMap = [
        'School Wide' => 'ALL',
        'Elementary School' => 'ES',
        'Middle School' => 'MS',
        'High School' => 'HS',
    ];

    public function handle(): int
    {
        $path = $this->option('file') ?: base_path('public/employee_details.csv');
        if (! is_file($path)) {
            $path = base_path($path);
        }
        if (! is_file($path)) {
            $this->error("CSV file not found: {$path}");
            return 1;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error('Could not open CSV file.');
            return 1;
        }

        $headers = array_map('trim', (array) fgetcsv($handle));
        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter(array_map('trim', $row)))) {
                continue;
            }
            $rows[] = array_combine($headers, array_pad($row, count($headers), ''));
        }
        fclose($handle);

        $this->info('Parsed ' . count($rows) . ' rows from CSV.');

        $divisions = Division::all()->keyBy('name');
        $missingDivisions = collect(['ALL', 'ES', 'MS', 'HS', 'NTS'])->diff($divisions->keys());
        if ($missingDivisions->isNotEmpty()) {
            $this->error('Missing divisions: ' . $missingDivisions->implode(', ') . '. Run DivisionSeeder first.');
            return 1;
        }

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN - no changes will be made');
            $sample = array_slice($rows, 0, 5);
            foreach ($sample as $i => $row) {
                $name = trim(($row['First Name'] ?? '') . ' ' . ($row['Last Name'] ?? ''));
                $email = trim($row['Work Email'] ?? '');
                $bu = trim($row['Business Unit'] ?? '');
                $divName = $this->mapBusinessUnitToDivision($bu);
                $this->line("  " . ($i + 1) . ". {$email} | {$name} | {$bu} → {$divName}");
            }
            $this->info('... and ' . (count($rows) - 5) . ' more rows');
            return 0;
        }

        $this->info('Clearing all users...');
        $deleted = User::query()->count();
        User::query()->delete();
        $this->info("Deleted {$deleted} users.");

        $created = 0;
        $skipped = 0;
        $errors = [];
        $placeholderPassword = Hash::make(Str::random(32)); // One hash for all; OAuth users don't use password

        foreach ($rows as $i => $row) {
            $email = trim($row['Work Email'] ?? '');
            if (empty($email) || ! str_contains($email, '@')) {
                $skipped++;
                continue;
            }

            $firstName = trim($row['First Name'] ?? '');
            $lastName = trim($row['Last Name'] ?? '');
            $name = trim($firstName . ' ' . $lastName) ?: $email;

            $bu = trim($row['Business Unit'] ?? '');
            $divName = $this->mapBusinessUnitToDivision($bu);
            $division = $divisions->get($divName);
            $divisionId = $division?->id;

            try {
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $placeholderPassword,
                    'division_id' => $divisionId,
                    'is_admin' => false,
                    'is_active' => true,
                ]);
                $created++;
            } catch (\Throwable $e) {
                $errors[] = "Row " . ($i + 2) . " ({$email}): " . $e->getMessage();
            }
        }

        $this->info("Imported {$created} users. Skipped {$skipped} rows with invalid email.");
        if (! empty($errors)) {
            foreach ($errors as $err) {
                $this->error('  ' . $err);
            }
        }

        return 0;
    }

    private function mapBusinessUnitToDivision(string $businessUnit): string
    {
        $bu = trim($businessUnit);
        return $this->divisionMap[$bu] ?? 'NTS';
    }
}
