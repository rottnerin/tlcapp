<?php

namespace App\Console\Commands;

use App\Models\UserSelectedSession;
use App\Models\ScheduleItem;
use App\Models\WellnessSession;
use App\Models\PLWednesdaySession;
use App\Models\CCLSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanOrphanedSelections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'selections:clean-orphaned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove orphaned user session selections where the selectable record no longer exists';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting orphaned selections cleanup...');

        $totalDeleted = 0;

        DB::transaction(function () use (&$totalDeleted) {
            // Define model classes and their table names
            $modelTypes = [
                ScheduleItem::class => (new ScheduleItem())->getTable(),
                WellnessSession::class => (new WellnessSession())->getTable(),
                PLWednesdaySession::class => (new PLWednesdaySession())->getTable(),
                CCLSession::class => (new CCLSession())->getTable(),
            ];

            $deletedCounts = [];

            foreach ($modelTypes as $modelClass => $tableName) {
                $deleted = UserSelectedSession::where('selectable_type', $modelClass)
                    ->whereNotExists(function ($query) use ($tableName) {
                        $query->select(DB::raw(1))
                            ->from($tableName)
                            ->whereColumn("{$tableName}.id", 'user_selected_sessions.selectable_id');
                    })
                    ->delete();

                $deletedCounts[$modelClass] = $deleted;
                $totalDeleted += $deleted;
            }

            $this->info("Cleaned up orphaned selections:");
            $this->info("  - ScheduleItem: {$deletedCounts[ScheduleItem::class]}");
            $this->info("  - WellnessSession: {$deletedCounts[WellnessSession::class]}");
            $this->info("  - PLWednesdaySession: {$deletedCounts[PLWednesdaySession::class]}");
            $this->info("  - CCLSession: {$deletedCounts[CCLSession::class]}");
        });

        $this->info("✅ Total orphaned selections cleaned: {$totalDeleted}");

        return 0;
    }
}
