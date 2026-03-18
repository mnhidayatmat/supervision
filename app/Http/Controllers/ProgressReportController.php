<?php

namespace App\Http\Controllers;

use App\Models\ProgressReport;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressReportController extends Controller
{
    public function index(Student $student)
    {
        $this->authorize('view', $student);
        $reports = $student->progressReports()->latest()->paginate(10);
        return view('reports.index', compact('student', 'reports'));
    }

    public function create(Student $student)
    {
        return view('reports.create', compact('student'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'achievements' => 'nullable|string',
            'challenges' => 'nullable|string',
            'next_steps' => 'nullable|string',
            'type' => 'required|in:weekly,monthly,milestone,custom',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date',
        ]);

        $student->progressReports()->create([
            ...$validated,
            'status' => $request->has('submit') ? 'submitted' : 'draft',
            'submitted_at' => $request->has('submit') ? now() : null,
        ]);

        return redirect()->route('reports.index', $student)->with('success', 'Report saved.');
    }

    public function show(Student $student, ProgressReport $report)
    {
        $this->authorize('view', $report);
        $report->load('revisions.comments.user');
        return view('reports.show', compact('student', 'report'));
    }

    public function edit(Student $student, ProgressReport $report)
    {
        $this->authorize('update', $report);
        return view('reports.edit', compact('student', 'report'));
    }

    public function update(Request $request, Student $student, ProgressReport $report)
    {
        $this->authorize('update', $report);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'achievements' => 'nullable|string',
            'challenges' => 'nullable|string',
            'next_steps' => 'nullable|string',
            'type' => 'required|in:weekly,monthly,milestone,custom',
        ]);

        $report->update([
            ...$validated,
            'status' => $request->has('submit') ? 'submitted' : 'draft',
            'submitted_at' => $request->has('submit') ? now() : $report->submitted_at,
        ]);

        return redirect()->route('reports.show', [$student, $report])->with('success', 'Report updated.');
    }

    public function review(Request $request, Student $student, ProgressReport $report)
    {
        $this->authorize('review', $report);

        $validated = $request->validate([
            'supervisor_feedback' => 'required|string',
            'decision' => 'required|in:accepted,revision_needed',
        ]);

        $report->update([
            'supervisor_feedback' => $validated['supervisor_feedback'],
            'status' => $validated['decision'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        if ($validated['decision'] === 'revision_needed') {
            $report->revisions()->create([
                'requested_by' => Auth::id(),
                'assigned_to' => $student->user_id,
                'description' => $validated['supervisor_feedback'],
            ]);
        }

        return back()->with('success', 'Report reviewed.');
    }
}
