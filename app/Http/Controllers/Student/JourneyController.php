<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JourneyController extends Controller
{
    /**
     * Display the student's research journey.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $this->getStudent($user, $request->route('student'));

        if (!$student) {
            return redirect()->route($this->getRedirectRoute())
                ->with('error', 'Student not found or access denied.');
        }

        // Get or create the student's research journey
        $journey = $student->researchJourneys()
            ->with(['stages.milestones', 'template.stages'])
            ->firstOrCreate(
                ['name' => 'Research Journey'],
                [
                    'journey_template_id' => $this->getDefaultTemplate($student),
                    'start_date' => $student->start_date ?? now(),
                    'status' => 'in_progress',
                    'progress' => 0,
                ]
            );

        // Ensure stages exist
        if ($journey->stages->isEmpty() && $journey->template) {
            $this->createStagesFromTemplate($journey);
            $journey->load('stages.milestones');
        }

        return view('student.journey.index', [
            'student' => $student,
            'journey' => $journey,
            'students' => $this->getAccessibleStudents($user),
        ]);
    }

    /**
     * Get journey data for API.
     */
    public function data(Student $student)
    {
        $this->authorize('view', $student);

        $journey = $student->researchJourneys()
            ->with(['stages.milestones.tasks', 'template'])
            ->firstOrFail();

        $timelineData = $this->buildTimelineData($journey);

        return response()->json([
            'journey' => [
                'id' => $journey->id,
                'name' => $journey->name,
                'start_date' => $journey->start_date?->format('Y-m-d'),
                'end_date' => $journey->end_date?->format('Y-m-d'),
                'progress' => $journey->progress,
                'status' => $journey->status,
            ],
            'stages' => $journey->stages->map(fn($stage) => [
                'id' => $stage->id,
                'name' => $stage->name,
                'description' => $stage->description,
                'start_date' => $stage->start_date?->format('Y-m-d'),
                'end_date' => $stage->end_date?->format('Y-m-d'),
                'progress' => $stage->progress,
                'status' => $stage->status,
                'sort_order' => $stage->sort_order,
                'milestones_count' => $stage->milestones->count(),
                'completed_milestones' => $stage->milestones->where('status', 'completed')->count(),
            ]),
            'timeline' => $timelineData,
            'stats' => $this->calculateJourneyStats($journey),
        ]);
    }

    /**
     * Update journey stage progress.
     */
    public function updateStage(Request $request, Student $student, $stageId)
    {
        $this->authorize('view', $student);

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'nullable|in:not_started,in_progress,completed',
        ]);

        $stage = $student->researchJourneys()
            ->firstOrFail()
            ->stages()
            ->findOrFail($stageId);

        $stage->update($validated);

        // Update journey progress
        $this->recalculateJourneyProgress($stage->journey);

        return response()->json([
            'success' => true,
            'stage' => $stage->fresh(),
        ]);
    }

    /**
     * Build timeline data for Gantt chart.
     */
    protected function buildTimelineData($journey)
    {
        $tasks = collect();
        $weekOffset = 0;

        foreach ($journey->stages->sortBy('sort_order') as $stage) {
            $stageStart = $journey->start_date?->copy()->addWeeks($weekOffset);
            $stageDuration = $stage->end_date
                ? $stage->start_date->diffInDays($stage->end_date)
                : ($stage->templateStage?->duration_weeks * 7 ?? 30);

            // Add stage as a task
            $tasks->push([
                'id' => 'stage-' . $stage->id,
                'name' => $stage->name,
                'start' => $stageStart->format('Y-m-d'),
                'end' => $stageStart->copy()->addDays($stageDuration)->format('Y-m-d'),
                'progress' => $stage->progress ?? 0,
                'dependencies' => '',
                'custom_class' => 'gantt-stage gantt-stage-' . $stage->status,
                'type' => 'stage',
                'stage_id' => $stage->id,
            ]);

            // Add milestones
            foreach ($stage->milestones->sortBy('sort_order') as $milestone) {
                if ($milestone->due_date) {
                    $tasks->push([
                        'id' => 'milestone-' . $milestone->id,
                        'name' => $milestone->name,
                        'start' => $milestone->due_date->format('Y-m-d'),
                        'end' => $milestone->due_date->copy()->addDays(1)->format('Y-m-d'),
                        'progress' => $milestone->status === 'completed' ? 100 : 0,
                        'dependencies' => 'stage-' . $stage->id,
                        'custom_class' => 'gantt-milestone',
                        'type' => 'milestone',
                        'milestone_id' => $milestone->id,
                    ]);
                }
            }

            $weekOffset += $stageDuration / 7;
        }

        return $tasks->toArray();
    }

    /**
     * Calculate journey statistics.
     */
    protected function calculateJourneyStats($journey)
    {
        $stages = $journey->stages;
        $milestones = $stages->flatMap->milestones;

        return [
            'total_stages' => $stages->count(),
            'completed_stages' => $stages->where('status', 'completed')->count(),
            'total_milestones' => $milestones->count(),
            'completed_milestones' => $milestones->where('status', 'completed')->count(),
            'overall_progress' => $journey->progress ?? 0,
            'elapsed_weeks' => $journey->start_date
                ? ceil(now()->diffInWeeks($journey->start_date))
                : 0,
        ];
    }

    /**
     * Recalculate journey progress based on stages.
     */
    protected function recalculateJourneyProgress($journey)
    {
        if ($journey->stages->isEmpty()) {
            $journey->update(['progress' => 0]);
            return;
        }

        $avgProgress = $journey->stages->avg('progress') ?? 0;
        $journey->update(['progress' => round($avgProgress)]);
    }

    /**
     * Create stages from template.
     */
    protected function createStagesFromTemplate($journey)
    {
        if (!$journey->template) return;

        $startDate = $journey->start_date ?? now();
        $currentWeek = 0;

        foreach ($journey->template->stages->sortBy('sort_order') as $templateStage) {
            $stageStart = $startDate->copy()->addWeeks($currentWeek);
            $stageEnd = $stageStart->copy()->addWeeks($templateStage->duration_weeks);

            $stage = $journey->stages()->create([
                'name' => $templateStage->name,
                'description' => $templateStage->description,
                'sort_order' => $templateStage->sort_order,
                'start_date' => $stageStart,
                'end_date' => $stageEnd,
                'status' => 'not_started',
                'progress' => 0,
            ]);

            // Create milestones
            foreach ($templateStage->milestones->sortBy('sort_order') as $templateMilestone) {
                $stage->milestones()->create([
                    'name' => $templateMilestone->name,
                    'description' => $templateMilestone->description,
                    'sort_order' => $templateMilestone->sort_order,
                    'due_date' => $startDate->copy()->addWeeks($templateMilestone->week_offset),
                    'status' => 'not_started',
                    'progress' => 0,
                ]);
            }

            $currentWeek += $templateStage->duration_weeks;
        }
    }

    /**
     * Get default template for student's programme.
     */
    protected function getDefaultTemplate($student)
    {
        if (!$student->programme_id) return null;

        return \App\Models\JourneyTemplate::where('programme_id', $student->programme_id)
            ->where('is_default', true)
            ->first()?->id;
    }

    /**
     * Get student based on user role.
     */
    protected function getStudent($user, $studentParam)
    {
        if ($user->role === 'student') {
            return $user->student;
        }

        if ($studentParam) {
            $student = Student::findOrFail($studentParam);
            $this->authorize('view', $student);
            return $student;
        }

        return $user->role === 'admin'
            ? Student::where('status', 'active')->first()
            : $user->supervisorStudents?->first() ?? $user->cosupervisorStudents?->first();
    }

    /**
     * Get accessible students for the current user.
     */
    protected function getAccessibleStudents($user)
    {
        return match ($user->role) {
            'admin' => Student::with(['user', 'programme'])
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get(),
            'supervisor', 'cosupervisor' => Student::with(['user', 'programme'])
                ->where(function ($query) use ($user) {
                    $query->where('supervisor_id', $user->id)
                        ->orWhere('cosupervisor_id', $user->id);
                })
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get(),
            'student' => collect([$user->student])->filter(),
            default => collect(),
        };
    }

    /**
     * Get redirect route based on role.
     */
    protected function getRedirectRoute()
    {
        return match (Auth::user()->role) {
            'admin' => 'admin.dashboard',
            'supervisor', 'cosupervisor' => 'supervisor.dashboard',
            'student' => 'student.dashboard',
            default => 'dashboard',
        };
    }
}
