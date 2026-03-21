<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $student = $this->effectiveStudent();

        if (!$student) {
            abort(404, 'Student not found.');
        }

        $tasks = $student->tasks()
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $tasksByStatus = $tasks->groupBy('status');

        $upcomingTasks = $student->tasks()
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->where('status', '!=', 'completed')
            ->orderBy('due_date')
            ->take(5)->get();

        $recentReports = $student->progressReports()->latest()->take(3)->get();
        $upcomingMeetings = $student->meetings()->where('scheduled_at', '>=', now())->where('status', 'scheduled')->orderBy('scheduled_at')->take(3)->get();

        return view('student.dashboard', compact('student', 'tasks', 'tasksByStatus', 'upcomingTasks', 'recentReports', 'upcomingMeetings'));
    }
}
