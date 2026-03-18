<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    protected $fillable = [
        'research_journey_id', 'name', 'description', 'sort_order',
        'start_date', 'end_date', 'status', 'progress',
    ];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }

    public function journey(): BelongsTo { return $this->belongsTo(ResearchJourney::class, 'research_journey_id'); }
    public function milestones(): HasMany { return $this->hasMany(Milestone::class); }
}
