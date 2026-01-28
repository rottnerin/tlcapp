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
            // Clean up orphaned ScheduleItem selections
            $deletedSchedule = UserSelectedSession::where('selectable_type', ScheduleItem::class)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('schedule_items')
                        ->whereColumn('schedule_items.id', 'user_selected_sessions.selectable_id');
                })
                ->delete();

            // Clean up orphaned WellnessSession selections
            $deletedWellness = UserSelectedSession::where('selectable_type', WellnessSession::class)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('wellness_sessions')
                        ->whereColumn('wellness_sessions.id', 'user_selected_sessions.selectable_id');
                })
                ->delete();

            // Clean up orphaned PLWednesdaySession selections
            $deletedPLWednesday = UserSelectedSession::where('selectable_type', PLWednesdaySession::class)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('p_l_wednesday_sessions')
                        ->whereColumn('p_l_wednesday_sessions.id', 'user_selected_sessions.selectable_id');
                })
                ->delete();

            // Clean up orphaned CCLSession selections
            $deletedCCL = UserSelectedSession::where('selectable_type', CCLSession::class)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('c_c_l_sessions')
                        ->whereColumn('c_c_l_sessions.id', 'user_selected_sessions.selectable_id');
                })
                ->delete();

            $totalDeleted = $deletedSchedule + $deletedWellness + $deletedPLWednesday + $deletedCCL;

            $this->info("Cleaned up orphaned selections:");
            $this->info("  - ScheduleItem: {$deletedSchedule}");
            $this->info("  - WellnessSession: {$deletedWellness}");
            $this->info("  - PLWednesdaySession: {$deletedPLWednesday}");
            $this->info("  - CCLSession: {$deletedCCL}");
        });

        $this->info("✅ Total orphaned selections cleaned: {$totalDeleted}");

        return 0;
    }
}
