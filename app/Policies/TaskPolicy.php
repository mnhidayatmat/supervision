<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        if ($user->isAdmin()) return true;
        $student = $task->student;
        return $user->id === $student->user_id
            || $user->id === $student->supervisor_id
            || $user->id === $student->cosupervisor_id;
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can create tasks
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->isAdmin()) return true;
        $student = $task->student;
        return $user->id === $student->user_id
            || $user->id === $student->supervisor_id
            || $user->id === $student->cosupervisor_id;
    }

    public function review(User $user, Task $task): bool
    {
        if ($user->isAdmin()) return true;
        $student = $task->student;
        return $user->id === $student->supervisor_id
            || $user->id === $student->cosupervisor_id;
    }
}
