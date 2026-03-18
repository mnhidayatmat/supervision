<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function index(Student $student)
    {
        $this->authorize('view', $student);
        $meetings = $student->meetings()->with('creator', 'actionItems')->latest('scheduled_at')->paginate(10);
        return view('meetings.index', compact('student', 'meetings'));
    }

    public function create(Student $student)
    {
        $this->authorize('view', $student);
        return view('meetings.create', compact('student'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'agenda' => 'nullable|string',
            'type' => 'required|in:supervision,progress_review,viva,other',
            'mode' => 'required|in:in_person,online,hybrid',
            'location' => 'nullable|string',
            'meeting_link' => 'nullable|url',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'nullable|integer|min:15',
        ]);

        $meeting = $student->meetings()->create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        // Add attendees
        $attendeeIds = [$student->user_id];
        if ($student->supervisor_id) $attendeeIds[] = $student->supervisor_id;
        if ($student->cosupervisor_id) $attendeeIds[] = $student->cosupervisor_id;
        $meeting->attendees()->attach($attendeeIds);

        return redirect()->route('meetings.index', $student)->with('success', 'Meeting scheduled.');
    }

    public function show(Student $student, Meeting $meeting)
    {
        $this->authorize('view', $student);
        $meeting->load('attendees', 'actionItems.assignee');
        return view('meetings.show', compact('student', 'meeting'));
    }

    public function update(Request $request, Student $student, Meeting $meeting)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'action_items' => 'nullable|array',
            'action_items.*.description' => 'required|string',
            'action_items.*.assigned_to' => 'nullable|exists:users,id',
            'action_items.*.due_date' => 'nullable|date',
        ]);

        $meeting->update([
            'notes' => $validated['notes'],
            'status' => $validated['status'],
        ]);

        if (!empty($validated['action_items'])) {
            foreach ($validated['action_items'] as $item) {
                $meeting->actionItems()->create($item);
            }
        }

        return redirect()->route('meetings.show', [$student, $meeting])->with('success', 'Meeting updated.');
    }
}
