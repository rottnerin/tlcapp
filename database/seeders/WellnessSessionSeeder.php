<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WellnessSession;
use App\Models\WellnessSetting;
use App\Models\PDDay;
use Carbon\Carbon;

class WellnessSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure Wellness is active
        WellnessSetting::initialize();
        $settings = WellnessSetting::getActive();
        $settings->is_active = true;
        $settings->save();

        // Get or create a spring PD day
        $springPDDay = PDDay::spring()->active()->first();
        
        if (!$springPDDay) {
            // Create a spring PD day if it doesn't exist
            $springPDDay = PDDay::create([
                'title' => 'Spring 2026 Professional Learning Days',
                'description' => 'Spring professional development sessions including Wellness and TTT.',
                'start_date' => Carbon::parse('2026-03-02'),
                'end_date' => Carbon::parse('2026-03-03'),
                'is_active' => true,
                'season' => 'spring',
                'academic_year' => PDDay::getCurrentAcademicYear(),
            ]);
        }

        // Create wellness sessions (all at 14:30-15:30 as per model)
        $sessions = [
            [
                'title' => 'Yoga and Mindfulness',
                'description' => 'Join us for a relaxing yoga session focused on mindfulness and stress relief. Suitable for all levels. Bring a yoga mat if you have one.',
                'presenter_name' => 'Priya Sharma',
                'presenter_email' => 'priya.sharma@aes.ac.in',
                'presenter_bio' => 'Priya is a certified yoga instructor with 10 years of experience in mindfulness practices.',
                'location' => 'Gymnasium',
                'date' => Carbon::parse('2026-03-02'),
                'max_participants' => 25,
                'current_enrollment' => 0,
                'category' => ['Yoga', 'Mindfulness', 'Wellness'],
                'equipment_needed' => 'Yoga mats (some provided)',
                'special_requirements' => 'Comfortable clothing recommended',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Meditation and Breathing Techniques',
                'description' => 'Learn practical meditation and breathing techniques to reduce stress and improve focus. Perfect for beginners and experienced practitioners.',
                'presenter_name' => 'Raj Patel',
                'presenter_email' => 'raj.patel@aes.ac.in',
                'presenter_bio' => 'Raj has been practicing meditation for 15 years and teaches mindfulness-based stress reduction.',
                'location' => 'Quiet Room 101',
                'date' => Carbon::parse('2026-03-02'),
                'max_participants' => 20,
                'current_enrollment' => 0,
                'category' => ['Meditation', 'Wellness', 'Stress Relief'],
                'equipment_needed' => 'Cushions provided',
                'special_requirements' => 'Quiet environment',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Zumba Fitness',
                'description' => 'Get moving with an energetic Zumba class! Fun dance fitness that combines Latin and international music with dance moves.',
                'presenter_name' => 'Maria Santos',
                'presenter_email' => 'maria.santos@aes.ac.in',
                'presenter_bio' => 'Maria is a certified Zumba instructor who brings energy and fun to every class.',
                'location' => 'Dance Studio',
                'date' => Carbon::parse('2026-03-02'),
                'max_participants' => 30,
                'current_enrollment' => 0,
                'category' => ['Fitness', 'Dance', 'Wellness'],
                'equipment_needed' => 'Water bottle recommended',
                'special_requirements' => 'Comfortable workout clothes and shoes',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Nature Walk and Outdoor Meditation',
                'description' => 'Join us for a peaceful nature walk followed by outdoor meditation. Connect with nature and find inner peace.',
                'presenter_name' => 'Anita Kumar',
                'presenter_email' => 'anita.kumar@aes.ac.in',
                'presenter_bio' => 'Anita is an outdoor wellness enthusiast and meditation guide.',
                'location' => 'School Grounds - Meet at Main Entrance',
                'date' => Carbon::parse('2026-03-03'),
                'max_participants' => 15,
                'current_enrollment' => 0,
                'category' => ['Outdoor', 'Meditation', 'Wellness'],
                'equipment_needed' => 'Comfortable walking shoes',
                'special_requirements' => 'Weather dependent - will move indoors if needed',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Tai Chi for Stress Relief',
                'description' => 'Learn gentle Tai Chi movements designed to reduce stress and improve balance. Perfect for all fitness levels.',
                'presenter_name' => 'Chen Wei',
                'presenter_email' => 'chen.wei@aes.ac.in',
                'presenter_bio' => 'Chen is a Tai Chi master with 20 years of teaching experience.',
                'location' => 'Gymnasium',
                'date' => Carbon::parse('2026-03-03'),
                'max_participants' => 20,
                'current_enrollment' => 0,
                'category' => ['Tai Chi', 'Wellness', 'Stress Relief'],
                'equipment_needed' => 'None',
                'special_requirements' => 'Comfortable, loose-fitting clothing',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Art Therapy and Creative Expression',
                'description' => 'Explore creative expression through art therapy. No artistic experience needed - just bring your creativity!',
                'presenter_name' => 'Lisa Thompson',
                'presenter_email' => 'lisa.thompson@aes.ac.in',
                'presenter_bio' => 'Lisa is a licensed art therapist specializing in stress reduction and self-expression.',
                'location' => 'Art Room 205',
                'date' => Carbon::parse('2026-03-03'),
                'max_participants' => 18,
                'current_enrollment' => 0,
                'category' => ['Art Therapy', 'Wellness', 'Creative'],
                'equipment_needed' => 'All materials provided',
                'special_requirements' => 'Aprons provided to protect clothing',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Pilates Core Strength',
                'description' => 'Build core strength and improve posture with a Pilates session. Focus on controlled movements and breathing.',
                'presenter_name' => 'Sarah Johnson',
                'presenter_email' => 'sarah.johnson@aes.ac.in',
                'presenter_bio' => 'Sarah is a certified Pilates instructor with expertise in core strengthening and rehabilitation.',
                'location' => 'Fitness Studio',
                'date' => Carbon::parse('2026-03-02'),
                'max_participants' => 22,
                'current_enrollment' => 0,
                'category' => ['Pilates', 'Fitness', 'Wellness'],
                'equipment_needed' => 'Mats provided',
                'special_requirements' => 'Comfortable workout attire',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Sound Healing and Relaxation',
                'description' => 'Experience the therapeutic benefits of sound healing with singing bowls and gentle vibrations. Deep relaxation guaranteed.',
                'presenter_name' => 'David Chen',
                'presenter_email' => 'david.chen@aes.ac.in',
                'presenter_bio' => 'David is a certified sound healer and meditation teacher with 8 years of experience.',
                'location' => 'Meditation Hall',
                'date' => Carbon::parse('2026-03-02'),
                'max_participants' => 16,
                'current_enrollment' => 0,
                'category' => ['Sound Healing', 'Relaxation', 'Wellness'],
                'equipment_needed' => 'Mats and cushions provided',
                'special_requirements' => 'Please arrive 5 minutes early for setup',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Nutrition and Healthy Eating Workshop',
                'description' => 'Learn about balanced nutrition, meal planning, and healthy eating habits for busy educators. Includes practical tips and recipes.',
                'presenter_name' => 'Dr. Meera Kapoor',
                'presenter_email' => 'meera.kapoor@aes.ac.in',
                'presenter_bio' => 'Dr. Kapoor is a registered dietitian with a focus on nutrition for working professionals.',
                'location' => 'Conference Room B',
                'date' => Carbon::parse('2026-03-03'),
                'max_participants' => 25,
                'current_enrollment' => 0,
                'category' => ['Nutrition', 'Health', 'Wellness'],
                'equipment_needed' => 'Handouts and recipe cards provided',
                'special_requirements' => 'Note-taking materials welcome',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
            [
                'title' => 'Stretching and Flexibility',
                'description' => 'Improve flexibility and reduce muscle tension with guided stretching exercises. Perfect for those who sit for long periods.',
                'presenter_name' => 'Michael Brown',
                'presenter_email' => 'michael.brown@aes.ac.in',
                'presenter_bio' => 'Michael is a certified fitness trainer specializing in flexibility and mobility work.',
                'location' => 'Gymnasium',
                'date' => Carbon::parse('2026-03-03'),
                'max_participants' => 24,
                'current_enrollment' => 0,
                'category' => ['Stretching', 'Flexibility', 'Wellness'],
                'equipment_needed' => 'Mats provided',
                'special_requirements' => 'Comfortable clothing that allows movement',
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ],
        ];

        foreach ($sessions as $sessionData) {
            WellnessSession::create($sessionData);
        }

        $this->command->info('Created ' . count($sessions) . ' wellness sessions for Spring PL Days.');
    }
}
