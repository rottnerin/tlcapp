<?php

namespace App\Console\Commands;

use App\Models\CCLSession;
use App\Models\CCLSetting;
use App\Models\Division;
use App\Models\PDDay;
use App\Models\ScheduleItem;
use App\Models\UserSelectedSession;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCclMarch2026 extends Command
{
    protected $signature = 'ccl:import-march2026
                            {--file= : Path to .numbers file (default: public/CCL_Session_Schedule_March_2026.numbers)}
                            {--json= : Optional: path to pre-exported JSON (skips Python)}
                            {--replace : Clear existing CCL sessions before importing (required if sessions already exist)}';

    protected $description = 'Import March 2026 CCL sessions from the Numbers file. Day 1 = March 2, 1:30–2:30; Day 2 = March 3, 1:45–2:45. Use --replace to clear and re-import.';

    public function handle(): int
    {
        $jsonPath = $this->option('json');
        $numbersPath = $this->option('file') ?: base_path('public/CCL_Session_Schedule_March_2026.numbers');

        if ($jsonPath) {
            if (! is_file($jsonPath)) {
                $this->error("JSON file not found: {$jsonPath}");
                return 1;
            }
            $sessionsData = json_decode(file_get_contents($jsonPath), true);
        } else {
            if (! is_file($numbersPath)) {
                $this->error("Numbers file not found: {$numbersPath}");
                return 1;
            }
            $sessionsData = $this->runPythonParser($numbersPath);
        }

        if (! is_array($sessionsData)) {
            $this->error('Failed to parse sessions data.');
            return 1;
        }

        $day1Count = count(array_filter($sessionsData, fn ($s) => ($s['day'] ?? 0) == 1));
        $day2Count = count(array_filter($sessionsData, fn ($s) => ($s['day'] ?? 0) == 2));
        $this->info("Parsed {$day1Count} Day 1 sessions and {$day2Count} Day 2 sessions.");

        $existingCount = CCLSession::count();
        if ($existingCount > 0 && ! $this->option('replace')) {
            $this->error("There are already {$existingCount} CCL session(s). To clear and re-import, run with --replace");
            return 1;
        }
        if ($this->option('replace') && $existingCount > 0) {
            $this->clearExistingCclData();
        }

        $pdDay = $this->getOrCreateSpring2026PdDay();

        CCLSetting::getSettings()->update(['is_active' => true]);

        $created = 0;
        foreach ($sessionsData as $row) {
            $day = (int) ($row['day'] ?? 0);
            if ($day === 1) {
                $date = Carbon::parse('2026-03-02');
                $startTime = '13:30:00';
                $endTime = '14:30:00';
            } elseif ($day === 2) {
                $date = Carbon::parse('2026-03-03');
                $startTime = '13:45:00';
                $endTime = '14:45:00';
            } else {
                continue;
            }

            $coEmail = isset($row['co_presenter_email']) && str_contains((string) $row['co_presenter_email'], '@')
                ? $row['co_presenter_email']
                : null;

            $session = CCLSession::create([
                'title' => $row['session_title'],
                'description' => $row['description'] ?? null,
                'presenter_name' => $row['presenter_name'] ?? 'TBD',
                'presenter_email' => $row['email'] ?? null,
                'presenter_bio' => null,
                'co_presenter_name' => $row['co_presenter_name'] ?? null,
                'co_presenter_email' => $coEmail,
                'location' => null,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'contact_hours' => 1.0,
                'p_d_day_id' => $pdDay->id,
                'division_id' => null,
                'category' => null,
                'is_active' => true,
            ]);

            $startDateTime = $date->format('Y-m-d') . ' ' . $startTime;
            $endDateTime = $date->format('Y-m-d') . ' ' . $endTime;

            ScheduleItem::create([
                'title' => $session->title,
                'description' => $session->description,
                'location' => $session->location,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'date' => $date,
                'presenter_primary' => $session->presenter_name,
                'presenter_secondary' => $session->co_presenter_name,
                'presenter_bio' => $session->presenter_bio,
                'max_participants' => $this->parseMaxParticipants($row['max_participants'] ?? null),
                'current_enrollment' => 0,
                'equipment_needed' => $row['special_equipment'] ?? null,
                'special_requirements' => null,
                'is_active' => true,
                'session_type' => 'ccl',
                'p_d_day_id' => $pdDay->id,
            ]);

            $created++;
        }

        $this->info("Created {$created} CCL sessions and matching schedule items.");
        return 0;
    }

    private function clearExistingCclData(): void
    {
        $cclScheduleItemIds = ScheduleItem::where('session_type', 'ccl')->pluck('id');
        if ($cclScheduleItemIds->isNotEmpty()) {
            UserSession::whereIn('schedule_item_id', $cclScheduleItemIds)->delete();
        }
        ScheduleItem::where('session_type', 'ccl')->delete();
        UserSelectedSession::where('selectable_type', CCLSession::class)->delete();
        CCLSession::query()->delete();
        $this->info('Cleared existing CCL sessions, schedule items, and related enrollments.');
    }

    private function getOrCreateSpring2026PdDay(): PDDay
    {
        $pdDay = PDDay::spring()->active()->first();
        if ($pdDay) {
            $this->info("Using existing Spring PD Day: {$pdDay->title}");
            return $pdDay;
        }
        $pdDay = PDDay::create([
            'title' => 'Spring 2026 Professional Learning Days',
            'description' => 'Spring professional development sessions including CCL.',
            'start_date' => Carbon::parse('2026-03-02'),
            'end_date' => Carbon::parse('2026-03-03'),
            'is_active' => true,
            'season' => 'spring',
            'academic_year' => PDDay::getCurrentAcademicYear(),
        ]);
        $this->info("Created Spring PD Day: {$pdDay->title}");
        return $pdDay;
    }

    private function runPythonParser(string $numbersPath): ?array
    {
        $script = base_path('scripts/parse_ccl_numbers.py');
        if (! is_file($script)) {
            $this->error('scripts/parse_ccl_numbers.py not found.');
            return null;
        }
        $cmd = sprintf(
            '%s %s %s 2>/dev/null',
            escapeshellarg(PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3'),
            escapeshellarg($script),
            escapeshellarg($numbersPath)
        );
        $json = shell_exec($cmd);
        if (! $json) {
            $this->error('Python parser failed. Install: pip install numbers-parser');
            return null;
        }
        $decoded = json_decode(trim($json), true);
        if (isset($decoded['error'])) {
            $this->error($decoded['error']);
            return null;
        }
        return $decoded;
    }

    private function parseMaxParticipants(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $value = trim($value);
        if (preg_match('/^(\d+)/', $value, $m)) {
            return (int) $m[1];
        }
        return null;
    }
}
