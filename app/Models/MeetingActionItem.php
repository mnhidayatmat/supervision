<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingActionItem extends Model
{
    protected $fillable = [
        'meeting_id', 'assigned_to', 'description', 'due_date', 'is_completed', 'completed_at',
    ];

    protected function casts(): array
    {
        return ['due_date' => 'date', 'is_completed' => 'boolean', 'completed_at' => 'datetime'];
    }

    public function meeting(): BelongsTo { return $this->belongsTo(Meeting::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
}
