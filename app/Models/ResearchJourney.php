<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchJourney extends Model
{
    protected $fillable = [
        'student_id', 'journey_template_id', 'name', 'start_date', 'end_date', 'progress', 'status',
    ];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function template(): BelongsTo { return $this->belongsTo(JourneyTemplate::class, 'journey_template_id'); }
    public function stages(): HasMany { return $this->hasMany(Stage::class); }
}
