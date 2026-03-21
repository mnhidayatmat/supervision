<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Student $student)
    {
        $this->authorize('view', $student);

        $tasks = $student->tasks()
            ->whereNull('parent_id')
            ->with('subtasks')
            ->orderBy('sort_order')
            ->get();

        return view('tasks.index', compact('student', 'tasks'));
    }

    public function kanban(Student $student)
    {
        $this->authorize('view', $student);

        $tasks = $student->tasks()
            ->whereNull('parent_id')
            ->with('subtasks')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('status');

        return view('tasks.kanban', compact('student', 'tasks'));
    }

    public function gantt(Student $student)
    {
        $this->authorize('view', $student);

        $tasks = $student->tasks()
            ->whereNotNull('start_date')
            ->whereNotNull('due_date')
            ->orderBy('start_date')
            ->get();

        return view('tasks.gantt', compact('student', 'tasks'));
    }

    public function timelineOverview(Student $student)
    {
        $this->authorize('view', $student);

        $student->load(['user', 'programme']);

        // Get milestone tasks for timeline
        $milestoneTasks = $student->tasks()
            ->where('is_milestone', true)
            ->orderBy('due_date')
            ->get();

        return view('tasks.timeline-overview', compact('student', 'milestoneTasks'));
    }

    public function create(Student $student)
    {
        $this->authorize('view', $student);

        // Get all available milestones
        $milestones = \App\Models\Milestone::orderBy('name')->get();

        // Get parent tasks for subtask selection
        $parentTasks = $student->tasks()
            ->whereNull('parent_id')
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('tasks.create', compact('student', 'milestones', 'parentTasks'));
    }

    public function store(Request $request, Student $student)
    {
        $this->authorize('view', $student);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'milestone_id' => 'nullable|exists:milestones,id',
            'parent_id' => 'nullable|exists:tasks,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|integer',
        ]);

        $student->tasks()->create([
            ...$validated,
            'assigned_by' => Auth::id(),
            'status' => 'planned',
        ]);

        return redirect()->route('tasks.index', $student)->with('success', 'Task created.');
    }

    public function show(Student $student, Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['subtasks', 'milestone', 'assignedBy', 'dependencies', 'revisions.comments.user']);
        return view('tasks.show', compact('student', 'task'));
    }

    public function edit(Student $student, Task $task)
    {
        $this->authorize('update', $task);

        // Get all available milestones
        $milestones = \App\Models\Milestone::orderBy('name')->get();

        // Get parent tasks for subtask selection
        $parentTasks = $student->tasks()
            ->whereNull('parent_id')
            ->where('id', '!=', $task->id)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('tasks.edit', compact('student', 'task', 'milestones', 'parentTasks'));
    }

    public function update(Request $request, Student $student, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'milestone_id' => 'nullable|exists:milestones,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:' . implode(',', Task::STATUSES),
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'progress' => 'integer|min:0|max:100',
            'estimated_hours' => 'nullable|integer',
        ]);

        if ($validated['status'] === 'completed') {
            $validated['completed_at'] = now();
            $validated['progress'] = 100;
        }

        $task->update($validated);

        return redirect()->route('tasks.show', [$student, $task])->with('success', 'Task updated.');
    }

    public function destroy(Student $student, Task $task)
    {
        $this->authorize('update', $task);
        $task->delete();
        return redirect()->route('tasks.index', $student)->with('success', 'Task deleted.');
    }
}
