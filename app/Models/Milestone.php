<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    protected $fillable = [
        'name', 'description', 'sort_order', 'due_date', 'completed_at', 'status', 'progress',
    ];

    protected function casts(): array
    {
        return ['due_date' => 'date', 'completed_at' => 'date'];
    }

    public function tasks(): HasMany { return $this->hasMany(Task::class); }
}
