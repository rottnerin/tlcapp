<?php

namespace App\Console\Commands;

use App\Models\PDDay;
use App\Models\WellnessSession;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportWellbeingSessions extends Command
{
    protected $signature = 'wellness:import-wellbeing
                            {csv : Path to the Well-being and Belonging CSV (e.g. public/Well-being and Belonging Session-March 3, 2026 (Responses) - Form Responses 1.csv)}
                            {--date=2026-03-03 : Session date (Y-m-d) for all imported sessions}
                            {--replace : Delete existing wellness sessions on this date before importing}';

    protected $description = 'Import Well-being and Belonging sessions from Google Form CSV. Skips Time Stamp and Suggested Location columns.';

    public function handle(): int
    {
        $path = $this->argument('csv');
        if (! is_file($path) && ! is_file(base_path($path))) {
            $resolved = base_path($path);
            $this->error("CSV file not found: {$path}");
            if ($path !== $resolved) {
                $this->error("Tried: {$resolved}");
            }
            return 1;
        }

        $fullPath = is_file($path) ? realpath($path) : realpath(base_path($path));
        $sessionDate = Carbon::parse($this->option('date'));

        $pdDay = $this->getOrCreatePdDayForDate($sessionDate);
        if (! $pdDay) {
            $this->error('Could not find or create a PD Day for ' . $sessionDate->format('Y-m-d'));
            return 1;
        }

        if ($this->option('replace')) {
            $deleted = WellnessSession::whereDate('date', $sessionDate)->delete();
            if ($deleted > 0) {
                $this->info("Removed {$deleted} existing wellness session(s) for {$sessionDate->format('Y-m-d')}.");
            }
        }

        $handle = fopen($fullPath, 'r');
        if ($handle === false) {
            $this->error('Could not open CSV file.');
            return 1;
        }

        // Header row (may contain multiline quoted headers)
        $headers = fgetcsv($handle);
        $created = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter(array_map('trim', $row)))) {
                continue;
            }

            try {
                $session = $this->rowToWellnessSession($row, $sessionDate, $pdDay->id);
                if ($session) {
                    $session->save();
                    $created++;
                    $this->line("  Created: " . $session->title);
                }
            } catch (\Throwable $e) {
                $errors[] = 'Row ' . ($created + count($errors) + 1) . ': ' . $e->getMessage();
            }
        }

        fclose($handle);

        $this->info("Imported {$created} Well-being and Belonging session(s) for " . $sessionDate->format('F j, Y') . '.');

        if (! empty($errors)) {
            foreach ($errors as $err) {
                $this->warn($err);
            }
        }

        return 0;
    }

    /**
     * CSV columns (by index): 0=Time Stamp(skip), 1=Email, 2=Presenter's Full Name, 3=Co-Presenter(s),
     * 4=Session Description, 5=Suggested Location(skip), 6=Categories, 7=Max Participants, 8=Equipment needed
     */
    private function rowToWellnessSession(array $row, Carbon $date, int $pdDayId): ?WellnessSession
    {
        $email = isset($row[1]) ? trim($row[1]) : '';
        $presenterName = isset($row[2]) ? trim($row[2]) : '';
        $coPresenter = isset($row[3]) ? trim($row[3]) : null;
        $description = isset($row[4]) ? trim($row[4]) : '';
        $categoriesStr = isset($row[6]) ? trim($row[6]) : '';
        $maxParticipants = isset($row[7]) ? (int) preg_replace('/\D/', '', $row[7]) : 20;
        $equipmentNeeded = isset($row[8]) ? trim($row[8]) : null;

        if ($description === '') {
            throw new \InvalidArgumentException('Session description is required.');
        }

        $title = $this->titleFromDescription($description);
        $category = $this->parseCategories($categoriesStr);

        if ($maxParticipants < 1) {
            $maxParticipants = 20;
        }

        return new WellnessSession([
            'title' => $title,
            'description' => $description,
            'presenter_name' => $presenterName ?: null,
            'presenter_email' => $email ?: null,
            'co_presenter_name' => $coPresenter ?: null,
            'co_presenter_email' => null,
            'location' => null,
            'date' => $date,
            'max_participants' => $maxParticipants,
            'current_enrollment' => 0,
            'category' => $category,
            'equipment_needed' => $equipmentNeeded,
            'special_requirements' => null,
            'preparation_notes' => null,
            'is_active' => true,
            'source' => 'google_form',
            'external_id' => null,
            'p_d_day_id' => $pdDayId,
        ]);
    }

    private function titleFromDescription(string $description): string
    {
        $firstLine = trim(explode("\n", $description)[0]);
        if (str_contains($firstLine, ': ')) {
            return trim(explode(': ', $firstLine, 2)[0]);
        }
        if (strlen($firstLine) > 255) {
            return substr($firstLine, 0, 252) . '...';
        }
        return $firstLine ?: 'Well-being Session';
    }

    private function parseCategories(string $categoriesStr): array
    {
        if ($categoriesStr === '') {
            return [];
        }
        $list = array_map('trim', explode(',', $categoriesStr));
        return array_values(array_filter($list));
    }

    private function getOrCreatePdDayForDate(Carbon $date): ?PDDay
    {
        $pdDay = PDDay::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if ($pdDay) {
            return $pdDay;
        }

        $season = in_array($date->month, [1, 2, 3, 4, 5, 6, 7], true) ? 'spring' : 'fall';
        $pdDay = PDDay::create([
            'title' => ($season === 'spring' ? 'Spring' : 'Fall') . ' ' . $date->year . ' Professional Learning Days',
            'description' => 'Professional development including Well-being and Belonging sessions.',
            'start_date' => $date,
            'end_date' => $date,
            'is_active' => true,
            'season' => $season,
            'academic_year' => PDDay::getCurrentAcademicYear(),
        ]);
        $this->info("Created PD Day: {$pdDay->title}");

        return $pdDay;
    }
}
