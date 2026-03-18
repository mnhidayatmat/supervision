<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateStage extends Model
{
    protected $fillable = ['journey_template_id', 'name', 'description', 'sort_order', 'duration_weeks'];

    public function template(): BelongsTo { return $this->belongsTo(JourneyTemplate::class, 'journey_template_id'); }
    public function milestones(): HasMany { return $this->hasMany(TemplateMilestone::class); }
}
