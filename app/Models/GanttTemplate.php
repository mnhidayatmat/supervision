<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GanttTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'type',
        'data_source',
        'is_default',
        'is_active',
        'visual_config',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'visual_config' => 'array',
    ];

    /**
     * Get the user that owns the template.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the template.
     */
    public function items(): HasMany
    {
        return $this->hasMany(GanttTemplateItem::class)->orderBy('sort_order');
    }

    /**
     * Get root items (no parent).
     */
    public function rootItems(): HasMany
    {
        return $this->hasMany(GanttTemplateItem::class)
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }

    /**
     * Scope to get active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get templates by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get default templates.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get visual configuration with defaults.
     */
    public function getVisualConfig(): array
    {
        return array_merge([
            'theme' => 'default',
            'bar_height' => 30,
            'corner_radius' => 4,
            'view_mode' => 'day',
            'header_color' => '#FAFAF9',
            'bar_color' => '#D97706',
            'progress_color' => '#B45309',
            'text_color' => '#1C1917',
        ], $this->visual_config ?? []);
    }

    /**
     * Get available themes.
     */
    public static function getThemes(): array
    {
        return [
            'default' => [
                'name' => 'Amber (Default)',
                'bar_color' => '#D97706',
                'progress_color' => '#B45309',
                'header_color' => '#F7F7F5',
            ],
            'ocean' => [
                'name' => 'Ocean',
                'bar_color' => '#0284C7',
                'progress_color' => '#0369A1',
                'header_color' => '#F0F9FF',
            ],
            'forest' => [
                'name' => 'Forest',
                'bar_color' => '#059669',
                'progress_color' => '#047857',
                'header_color' => '#F0FDF4',
            ],
            'sunset' => [
                'name' => 'Sunset',
                'bar_color' => '#DC2626',
                'progress_color' => '#B91C1C',
                'header_color' => '#FEF2F2',
            ],
            'purple' => [
                'name' => 'Purple',
                'bar_color' => '#7C3AED',
                'progress_color' => '#6D28D9',
                'header_color' => '#F5F3FF',
            ],
            'monochrome' => [
                'name' => 'Monochrome',
                'bar_color' => '#374151',
                'progress_color' => '#1F2937',
                'header_color' => '#F9FAFB',
            ],
        ];
    }

    /**
     * Get a specific theme.
     */
    public static function getTheme(string $name): array
    {
        return self::getThemes()[$name] ?? self::getThemes()['default'];
    }
}
