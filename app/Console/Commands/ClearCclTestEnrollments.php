<?php

namespace App\Console\Commands;

use App\Models\ScheduleItem;
use App\Models\UserSession;
use Illuminate\Console\Command;

class ClearCclTestEnrollments extends Command
{
    protected $signature = 'ccl:clear-test-enrollments
                            {--session-title=CCL Capacity Test Session : CCL session title whose enrollments to clear (e.g. from CclCapacityEnrollmentTest)}';

    protected $description = 'Remove all enrollments for a CCL session (e.g. after running CclCapacityEnrollmentTest). Resets enrollment count on the schedule item.';

    public function handle(): int
    {
        $title = $this->option('session-title');
        if (! $title) {
            $this->error('Session title is required.');
            return 1;
        }

        $scheduleItem = ScheduleItem::where('session_type', 'ccl')
            ->where('title', $title)
            ->first();

        if (! $scheduleItem) {
            $this->warn("No CCL schedule item found with title: \"{$title}\".");
            return 0;
        }

        $enrollments = UserSession::where('schedule_item_id', $scheduleItem->id)->get();
        $confirmed = $enrollments->where('status', 'confirmed')->count();

        if ($enrollments->isEmpty()) {
            $this->info("No enrollments found for session: \"{$title}\".");
            return 0;
        }

        foreach ($enrollments as $e) {
            $e->update(['status' => 'cancelled']);
        }
        $scheduleItem->update(['current_enrollment' => 0]);

        $this->info("Cancelled {$enrollments->count()} enrollment(s) ({$confirmed} confirmed) for \"{$title}\" and reset capacity count.");
        return 0;
    }
}
