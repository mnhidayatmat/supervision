<?php

namespace App\Services;

use App\Models\JourneyTemplate;
use App\Models\ResearchJourney;
use App\Models\Student;

class JourneyService
{
    public function instantiateFromTemplate(Student $student, JourneyTemplate $template): ResearchJourney
    {
        $journey = ResearchJourney::create([
            'student_id' => $student->id,
            'journey_template_id' => $template->id,
            'name' => $template->name,
            'start_date' => $student->start_date ?? now(),
            'status' => 'not_started',
        ]);

        foreach ($template->stages()->orderBy('sort_order')->get() as $templateStage) {
            $stage = $journey->stages()->create([
                'name' => $templateStage->name,
                'description' => $templateStage->description,
                'sort_order' => $templateStage->sort_order,
            ]);

            foreach ($templateStage->milestones()->orderBy('sort_order')->get() as $templateMilestone) {
                $dueDate = $templateMilestone->week_offset
                    ? $journey->start_date?->addWeeks($templateMilestone->week_offset)
                    : null;

                $stage->milestones()->create([
                    'name' => $templateMilestone->name,
                    'description' => $templateMilestone->description,
                    'sort_order' => $templateMilestone->sort_order,
                    'due_date' => $dueDate,
                ]);
            }
        }

        return $journey->load('stages.milestones');
    }
}
