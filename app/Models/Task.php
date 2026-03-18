<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    const STATUSES = [
        'backlog', 'planned', 'in_progress', 'waiting_review', 'revision', 'completed',
    ];

    protected $fillable = [
        'student_id', 'milestone_id', 'assigned_by', 'parent_id', 'title',
        'description', 'status', 'priority', 'start_date', 'due_date',
        'completed_at', 'progress', 'sort_order', 'estimated_hours',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'completed_at' => 'date',
        ];
    }

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function milestone(): BelongsTo { return $this->belongsTo(Milestone::class); }
    public function assignedBy(): BelongsTo { return $this->belongsTo(User::class, 'assigned_by'); }
    public function parent(): BelongsTo { return $this->belongsTo(Task::class, 'parent_id'); }
    public function subtasks(): HasMany { return $this->hasMany(Task::class, 'parent_id'); }
    public function revisions(): MorphMany { return $this->morphMany(Revision::class, 'revisable'); }

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_id');
    }

    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_id', 'task_id');
    }
}
