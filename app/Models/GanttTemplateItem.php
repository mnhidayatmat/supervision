<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GanttTemplateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gantt_template_id',
        'parent_id',
        'name',
        'description',
        'item_type',
        'start_offset',
        'duration_days',
        'progress',
        'color',
        'sort_order',
        'dependencies',
    ];

    protected $casts = [
        'start_offset' => 'integer',
        'duration_days' => 'integer',
        'progress' => 'integer',
        'sort_order' => 'integer',
        'dependencies' => 'array',
    ];

    /**
     * Get the template that owns the item.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(GanttTemplate::class, 'gantt_template_id');
    }

    /**
     * Get the parent item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(GanttTemplateItem::class, 'parent_id');
    }

    /**
     * Get child items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(GanttTemplateItem::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get start date based on project start.
     */
    public function getStartDate(\DateTime $projectStart = null): \DateTime
    {
        $start = $projectStart ?? new \DateTime();
        if ($this->start_offset !== null) {
            $start->modify("+{$this->start_offset} days");
        }
        return clone $start;
    }

    /**
     * Get end date based on start date and duration.
     */
    public function getEndDate(\DateTime $projectStart = null): \DateTime
    {
        $start = $this->getStartDate($projectStart);
        if ($this->duration_days !== null) {
            $start->modify("+{$this->duration_days} days");
        }
        return $start;
    }

    /**
     * Convert to Gantt chart data format.
     */
    public function toGanttData(\DateTime $projectStart = null, int $index = null): array
    {
        $start = $this->getStartDate($projectStart);
        $end = $this->getEndDate($projectStart);

        $data = [
            'name' => $this->name,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'progress' => $this->progress,
        ];

        if ($index !== null) {
            $data['id'] = "task-{$index}";
        }

        // Add dependencies
        if (!empty($this->dependencies)) {
            $data['dependencies'] = implode(',', $this->dependencies);
        }

        // Add custom class for color
        if ($this->color) {
            $data['custom_class'] = 'gantt-item-' . ltrim($this->color, '#');
        }

        return $data;
    }
}
