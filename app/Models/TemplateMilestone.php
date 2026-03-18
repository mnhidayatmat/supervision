<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateMilestone extends Model
{
    protected $fillable = ['template_stage_id', 'name', 'description', 'sort_order', 'week_offset'];

    public function stage(): BelongsTo { return $this->belongsTo(TemplateStage::class, 'template_stage_id'); }
}
