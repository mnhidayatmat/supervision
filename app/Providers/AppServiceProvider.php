<?php

namespace App\Providers;

use App\Models\ProgressReport;
use App\Models\Student;
use App\Models\Task;
use App\Policies\ProgressReportPolicy;
use App\Policies\StudentPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(ProgressReport::class, ProgressReportPolicy::class);

        // Super admin gate
        Gate::before(function ($user, $ability) {
            if ($user->isAdmin() && !str_contains($ability, 'force')) {
                return true;
            }
        });
    }
}
