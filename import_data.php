<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Starting data import...\n";
    
    // Connect to source database
    $sourceDb = new PDO('sqlite:/Users/mhaque/Downloads/database.sqlite');
    $sourceDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Import Wellness Sessions
    echo "Importing wellness sessions...\n";
    
    // Clear existing wellness sessions
    DB::table('wellness_sessions')->truncate();
    
    $wellnessSessions = $sourceDb->query("SELECT * FROM wellbeing_sessions")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($wellnessSessions as $session) {
        DB::table('wellness_sessions')->insert([
            'title' => $session['title'],
            'description' => $session['description'],
            'presenter_name' => $session['facilitator'],
            'presenter_email' => $session['facilitator_email'],
            'location' => $session['room'],
            'date' => $session['day'],
            'start_time' => $session['time_start'],
            'end_time' => $session['time_end'],
            'max_participants' => $session['capacity'],
            'category' => $session['categories'] ?? 'wellness',
            'equipment_needed' => $session['special_equipment'],
            'is_active' => $session['is_active'] ? true : false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    echo "Imported " . count($wellnessSessions) . " wellness sessions.\n";
    
    // Import Schedule Items
    echo "Importing schedule items...\n";
    
    // Clear existing schedule items
    DB::table('schedule_items')->truncate();
    
    $scheduleItems = $sourceDb->query("SELECT * FROM schedule_templates")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($scheduleItems as $item) {
        DB::table('schedule_items')->insert([
            'title' => $item['details'],
            'description' => $item['details'],
            'location' => $item['venue'],
            'date' => $item['day'],
            'start_time' => $item['day'] . ' ' . $item['time_start'],
            'end_time' => $item['day'] . ' ' . $item['time_end'],
            'session_type' => $item['session_type'] === 'fixed' ? 'fixed' : 'wellness',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    echo "Imported " . count($scheduleItems) . " schedule items.\n";
    
    // Show summary
    echo "\n=== Import Summary ===\n";
    echo "Wellness Sessions: " . DB::table('wellness_sessions')->count() . "\n";
    echo "Schedule Items: " . DB::table('schedule_items')->count() . "\n";
    echo "Divisions: " . DB::table('divisions')->count() . "\n";
    
    echo "\nData import completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
