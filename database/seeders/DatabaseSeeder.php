<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use App\Models\JourneyTemplate;
use App\Models\Meeting;
use App\Models\Milestone;
use App\Models\Programme;
use App\Models\ProgressReport;
use App\Models\ResearchJourney;
use App\Models\Stage;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Models\Task;
use App\Models\TemplateStage;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@researchflow.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'staff_id' => 'ADM001',
            'status' => 'active',
            'department' => 'Academic Affairs',
        ]);

        // ── Supervisors ──
        $sv1 = User::create([
            'name' => 'Dr. Sarah Chen',
            'email' => 'sarah@researchflow.test',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'staff_id' => 'SV001',
            'status' => 'active',
            'department' => 'Computer Science',
            'faculty' => 'Faculty of Computing',
        ]);

        $sv2 = User::create([
            'name' => 'Prof. Ahmad Razak',
            'email' => 'ahmad@researchflow.test',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'staff_id' => 'SV002',
            'status' => 'active',
            'department' => 'Software Engineering',
            'faculty' => 'Faculty of Computing',
        ]);

        $cosv = User::create([
            'name' => 'Dr. Lim Wei',
            'email' => 'lim@researchflow.test',
            'password' => Hash::make('password'),
            'role' => 'cosupervisor',
            'staff_id' => 'CSV001',
            'status' => 'active',
            'department' => 'Data Science',
            'faculty' => 'Faculty of Computing',
        ]);

        // ── Programmes ──
        $fyp = Programme::create(['name' => 'Final Year Project', 'code' => 'FYP', 'slug' => 'fyp', 'duration_months' => 8, 'sort_order' => 1]);
        $msc = Programme::create(['name' => 'Master of Science', 'code' => 'MSC', 'slug' => 'msc', 'duration_months' => 24, 'sort_order' => 2]);
        $phd = Programme::create(['name' => 'Doctor of Philosophy', 'code' => 'PHD', 'slug' => 'phd', 'duration_months' => 48, 'sort_order' => 3]);

        // ── Journey Templates ──
        $this->createFypTemplate($fyp);
        $mscTemplate = $this->createMscTemplate($msc);
        $this->createPhdTemplate($phd);

        // ── Students ──
        $st1User = User::create([
            'name' => 'Ali bin Hassan',
            'email' => 'ali@researchflow.test',
            'password' => Hash::make('password'),
            'role' => 'student',
            'matric_number' => 'MSC2024001',
            'status' => 'active',
        ]);

        $student1 = Student::create([
            'user_id' => $st1User->id,
            'programme_id' => $msc->id,
            'supervisor_id' => $sv1->id,
            'cosupervisor_id' => $cosv->id,
            'research_title' => 'Machine Learning Approach for Early Detection of Network Intrusion',
            'intake' => '2024/2025-1',
            'start_date' => now()->subMonths(6),
            'expected_completion' => now()->addMonths(18),
            'status' => 'active',
            'overall_progress' => 25,
        ]);

        $st2User = User::create([
            'name' => 'Nurul Aisyah',
            'email' => 'nurul@researchflow.test',
            'password' => Hash::make('password'),
            'role' => 'student',
            'matric_number' => 'FYP2025001',
            'status' => 'active',
        ]);

        $student2 = Student::create([
            'user_id' => $st2User->id,
            'programme_id' => $fyp->id,
            'supervisor_id' => $sv2->id,
            'research_title' => 'Smart Campus Navigation System Using AR Technology',
            'intake' => '2024/2025-2',
            'start_date' => now()->subMonths(2),
            'expected_completion' => now()->addMonths(6),
            'status' => 'active',
            'overall_progress' => 10,
        ]);

        // Pending student
        $st3User = User::create([
            'name' => 'John Smith',
            'email' => 'john@researchflow.test',
            'password' => Hash::make('password'),
            'role' => 'student',
            'matric_number' => 'PHD2025001',
            'status' => 'pending',
        ]);

        Student::create([
            'user_id' => $st3User->id,
            'programme_id' => $phd->id,
            'status' => 'pending',
        ]);

        // ── Instantiate journey for student 1 ──
        $journey = ResearchJourney::create([
            'student_id' => $student1->id,
            'journey_template_id' => $mscTemplate->id,
            'name' => 'MSc Research Journey',
            'start_date' => $student1->start_date,
            'status' => 'in_progress',
            'progress' => 25,
        ]);

        $stage1 = Stage::create(['research_journey_id' => $journey->id, 'name' => 'Proposal Phase', 'sort_order' => 0, 'status' => 'completed', 'progress' => 100]);
        $stage2 = Stage::create(['research_journey_id' => $journey->id, 'name' => 'Literature Review', 'sort_order' => 1, 'status' => 'in_progress', 'progress' => 50]);
        $stage3 = Stage::create(['research_journey_id' => $journey->id, 'name' => 'Methodology & Implementation', 'sort_order' => 2, 'status' => 'not_started']);
        $stage4 = Stage::create(['research_journey_id' => $journey->id, 'name' => 'Analysis & Writing', 'sort_order' => 3, 'status' => 'not_started']);

        $m1 = Milestone::create(['stage_id' => $stage1->id, 'name' => 'Proposal Approved', 'sort_order' => 0, 'status' => 'completed', 'progress' => 100, 'due_date' => now()->subMonths(4)]);
        $m2 = Milestone::create(['stage_id' => $stage2->id, 'name' => 'Literature Review Draft', 'sort_order' => 0, 'status' => 'in_progress', 'progress' => 60, 'due_date' => now()->addWeeks(2)]);
        $m3 = Milestone::create(['stage_id' => $stage3->id, 'name' => 'System Design Complete', 'sort_order' => 0, 'status' => 'not_started', 'due_date' => now()->addMonths(4)]);
        $m4 = Milestone::create(['stage_id' => $stage4->id, 'name' => 'Thesis Submitted', 'sort_order' => 0, 'status' => 'not_started', 'due_date' => now()->addMonths(16)]);

        // ── Tasks for student 1 ──
        Task::create(['student_id' => $student1->id, 'milestone_id' => $m1->id, 'assigned_by' => $sv1->id, 'title' => 'Write research proposal', 'status' => 'completed', 'priority' => 'high', 'progress' => 100, 'start_date' => now()->subMonths(5), 'due_date' => now()->subMonths(4), 'completed_at' => now()->subMonths(4), 'sort_order' => 0]);
        Task::create(['student_id' => $student1->id, 'milestone_id' => $m1->id, 'assigned_by' => $sv1->id, 'title' => 'Prepare proposal presentation', 'status' => 'completed', 'priority' => 'high', 'progress' => 100, 'start_date' => now()->subMonths(4)->subWeeks(1), 'due_date' => now()->subMonths(4), 'completed_at' => now()->subMonths(4), 'sort_order' => 1]);
        Task::create(['student_id' => $student1->id, 'milestone_id' => $m2->id, 'assigned_by' => $sv1->id, 'title' => 'Review 30 relevant papers', 'status' => 'in_progress', 'priority' => 'high', 'progress' => 70, 'start_date' => now()->subMonths(3), 'due_date' => now()->addWeeks(1), 'sort_order' => 2]);
        Task::create(['student_id' => $student1->id, 'milestone_id' => $m2->id, 'assigned_by' => $sv1->id, 'title' => 'Draft Chapter 2 - Literature Review', 'status' => 'in_progress', 'priority' => 'medium', 'progress' => 40, 'start_date' => now()->subWeeks(3), 'due_date' => now()->addWeeks(2), 'sort_order' => 3]);
        Task::create(['student_id' => $student1->id, 'assigned_by' => $sv1->id, 'title' => 'Set up experiment environment', 'status' => 'planned', 'priority' => 'medium', 'start_date' => now()->addWeeks(2), 'due_date' => now()->addMonths(1), 'sort_order' => 4]);
        Task::create(['student_id' => $student1->id, 'assigned_by' => $sv1->id, 'title' => 'Collect and preprocess dataset', 'status' => 'backlog', 'priority' => 'medium', 'start_date' => now()->addMonths(1), 'due_date' => now()->addMonths(2), 'sort_order' => 5]);
        Task::create(['student_id' => $student1->id, 'milestone_id' => $m2->id, 'assigned_by' => $sv1->id, 'title' => 'Submit literature review for review', 'status' => 'waiting_review', 'priority' => 'high', 'progress' => 90, 'start_date' => now()->subWeeks(1), 'due_date' => now(), 'sort_order' => 6]);

        // ── Progress Reports ──
        ProgressReport::create([
            'student_id' => $student1->id,
            'title' => 'Month 5 Progress Report',
            'content' => "Completed review of 20 papers on network intrusion detection.\nStarted drafting Chapter 2.\nIdentified 3 key ML approaches for comparison.",
            'achievements' => 'Completed 20/30 paper reviews. Identified key research gap.',
            'challenges' => 'Access to some IEEE papers required inter-library loan.',
            'next_steps' => 'Complete remaining 10 paper reviews. Finish Chapter 2 draft.',
            'type' => 'monthly',
            'status' => 'submitted',
            'period_start' => now()->subMonths(1),
            'period_end' => now(),
            'submitted_at' => now(),
        ]);

        ProgressReport::create([
            'student_id' => $student1->id,
            'reviewed_by' => $sv1->id,
            'title' => 'Month 4 Progress Report',
            'content' => "Proposal approved. Started literature review phase.\nSet up Mendeley library with 15 initial papers.",
            'achievements' => 'Research proposal approved by committee.',
            'type' => 'monthly',
            'status' => 'accepted',
            'supervisor_feedback' => 'Good progress. Focus on recent publications (2020+) for your literature review.',
            'period_start' => now()->subMonths(2),
            'period_end' => now()->subMonths(1),
            'submitted_at' => now()->subMonths(1),
            'reviewed_at' => now()->subMonths(1)->addDays(2),
        ]);

        // ── Meetings ──
        $meeting1 = Meeting::create([
            'student_id' => $student1->id,
            'created_by' => $sv1->id,
            'title' => 'Weekly Supervision #12',
            'agenda' => "1. Review literature review progress\n2. Discuss methodology options\n3. Set next month targets",
            'type' => 'supervision',
            'mode' => 'online',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'scheduled_at' => now()->addDays(2)->setHour(10),
            'duration_minutes' => 60,
            'status' => 'scheduled',
        ]);
        $meeting1->attendees()->attach([$st1User->id, $sv1->id, $cosv->id]);

        Meeting::create([
            'student_id' => $student1->id,
            'created_by' => $sv1->id,
            'title' => 'Weekly Supervision #11',
            'agenda' => "Review paper summaries",
            'notes' => "Discussed 5 key papers. Student needs to focus on comparison of ML vs DL approaches for IDS.",
            'type' => 'supervision',
            'mode' => 'in_person',
            'location' => 'Room 3.12, Faculty of Computing',
            'scheduled_at' => now()->subWeeks(1),
            'duration_minutes' => 45,
            'status' => 'completed',
        ]);

        // ── Create default folders ──
        app(FileService::class)->createDefaultFolders($student1);
        app(FileService::class)->createDefaultFolders($student2);

        // ── AI Providers ──
        AiProvider::create(['name' => 'OpenAI', 'slug' => 'openai', 'model' => 'gpt-4o-mini', 'is_active' => false]);
        AiProvider::create(['name' => 'Google Gemini', 'slug' => 'gemini', 'model' => 'gemini-pro', 'is_active' => false]);
        AiProvider::create(['name' => 'Custom (OpenAI Compatible)', 'slug' => 'custom', 'is_active' => false]);

        // ── System Settings ──
        SystemSetting::set('storage_disk', 'local', 'storage');
        SystemSetting::set('report_frequency', 'weekly', 'general');
        SystemSetting::set('app_name', 'ResearchFlow', 'general');

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Login credentials (password: password):');
        $this->command->info('  Admin:      admin@researchflow.test');
        $this->command->info('  Supervisor: sarah@researchflow.test');
        $this->command->info('  Student:    ali@researchflow.test');
    }

    private function createFypTemplate(Programme $programme): JourneyTemplate
    {
        $template = JourneyTemplate::create(['programme_id' => $programme->id, 'name' => 'FYP Standard Journey', 'is_default' => true]);
        $s1 = $template->stages()->create(['name' => 'Proposal', 'sort_order' => 0, 'duration_weeks' => 4]);
        $s1->milestones()->create(['name' => 'Topic Selection', 'sort_order' => 0, 'week_offset' => 2]);
        $s1->milestones()->create(['name' => 'Proposal Approved', 'sort_order' => 1, 'week_offset' => 4]);
        $s2 = $template->stages()->create(['name' => 'Development', 'sort_order' => 1, 'duration_weeks' => 16]);
        $s2->milestones()->create(['name' => 'Design Complete', 'sort_order' => 0, 'week_offset' => 8]);
        $s2->milestones()->create(['name' => 'Implementation Done', 'sort_order' => 1, 'week_offset' => 20]);
        $s3 = $template->stages()->create(['name' => 'Report & Presentation', 'sort_order' => 2, 'duration_weeks' => 8]);
        $s3->milestones()->create(['name' => 'Final Report', 'sort_order' => 0, 'week_offset' => 28]);
        $s3->milestones()->create(['name' => 'Presentation', 'sort_order' => 1, 'week_offset' => 30]);
        return $template;
    }

    private function createMscTemplate(Programme $programme): JourneyTemplate
    {
        $template = JourneyTemplate::create(['programme_id' => $programme->id, 'name' => 'MSc Research Journey', 'is_default' => true]);
        $s1 = $template->stages()->create(['name' => 'Proposal Phase', 'sort_order' => 0, 'duration_weeks' => 12]);
        $s1->milestones()->create(['name' => 'Proposal Approved', 'sort_order' => 0, 'week_offset' => 12]);
        $s2 = $template->stages()->create(['name' => 'Literature Review', 'sort_order' => 1, 'duration_weeks' => 16]);
        $s2->milestones()->create(['name' => 'Literature Review Complete', 'sort_order' => 0, 'week_offset' => 28]);
        $s3 = $template->stages()->create(['name' => 'Methodology & Implementation', 'sort_order' => 2, 'duration_weeks' => 24]);
        $s3->milestones()->create(['name' => 'System Design', 'sort_order' => 0, 'week_offset' => 36]);
        $s3->milestones()->create(['name' => 'Implementation Complete', 'sort_order' => 1, 'week_offset' => 52]);
        $s4 = $template->stages()->create(['name' => 'Analysis & Writing', 'sort_order' => 3, 'duration_weeks' => 24]);
        $s4->milestones()->create(['name' => 'Thesis Draft', 'sort_order' => 0, 'week_offset' => 80]);
        $s4->milestones()->create(['name' => 'Thesis Submitted', 'sort_order' => 1, 'week_offset' => 96]);
        return $template;
    }

    private function createPhdTemplate(Programme $programme): JourneyTemplate
    {
        $template = JourneyTemplate::create(['programme_id' => $programme->id, 'name' => 'PhD Research Journey', 'is_default' => true]);
        $s1 = $template->stages()->create(['name' => 'Coursework & Proposal', 'sort_order' => 0, 'duration_weeks' => 24]);
        $s1->milestones()->create(['name' => 'Proposal Defence', 'sort_order' => 0, 'week_offset' => 24]);
        $s2 = $template->stages()->create(['name' => 'Research Phase 1', 'sort_order' => 1, 'duration_weeks' => 48]);
        $s2->milestones()->create(['name' => 'First Publication', 'sort_order' => 0, 'week_offset' => 52]);
        $s2->milestones()->create(['name' => 'Candidature Defence', 'sort_order' => 1, 'week_offset' => 72]);
        $s3 = $template->stages()->create(['name' => 'Research Phase 2', 'sort_order' => 2, 'duration_weeks' => 48]);
        $s3->milestones()->create(['name' => 'Second Publication', 'sort_order' => 0, 'week_offset' => 120]);
        $s4 = $template->stages()->create(['name' => 'Thesis & Viva', 'sort_order' => 3, 'duration_weeks' => 48]);
        $s4->milestones()->create(['name' => 'Thesis Submitted', 'sort_order' => 0, 'week_offset' => 168]);
        $s4->milestones()->create(['name' => 'Viva Voce', 'sort_order' => 1, 'week_offset' => 180]);
        return $template;
    }
}
