<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AiChatPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $student = $user->student;
            $files = $student->files()->where('is_latest', true)->get();
            $folders = $student->folders()->get();
        } else {
            $student = null;
            $files = collect();
            $folders = collect();
        }

        return view('ai.chat', compact('student', 'files', 'folders'));
    }

    public function studentContext(Student $student)
    {
        $this->authorize('view', $student);
        $files = $student->files()->where('is_latest', true)->get();
        $folders = $student->folders()->get();

        return view('ai.chat', compact('student', 'files', 'folders'));
    }
}
