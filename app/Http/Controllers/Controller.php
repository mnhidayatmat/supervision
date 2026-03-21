<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use \Illuminate\Foundation\Validation\ValidatesRequests;

    /**
     * Get the effective student for the current context.
     * Returns the selected student when admin is viewing as a student,
     * otherwise returns the authenticated user's student relationship.
     */
    protected function effectiveStudent(): ?Student
    {
        $user = Auth::user();

        // If admin is viewing as a student, use the session student ID
        if ($user->role === 'admin' && session()->has('admin_view_as_student_id')) {
            return Student::with([
                'programme', 'supervisor', 'cosupervisor', 'user',
            ])->find(session()->get('admin_view_as_student_id'));
        }

        // Otherwise, use the user's student relationship
        return $user->student?->load([
            'programme', 'supervisor', 'cosupervisor', 'user',
        ]);
    }

    /**
     * Get the effective student ID for the current context.
     */
    protected function effectiveStudentId(): ?int
    {
        $user = Auth::user();

        if ($user->role === 'admin' && session()->has('admin_view_as_student_id')) {
            return (int) session()->get('admin_view_as_student_id');
        }

        return $user->student?->id;
    }
}
