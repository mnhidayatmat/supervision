<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JourneyTemplate extends Model
{
    protected $fillable = ['programme_id', 'name', 'description', 'is_default', 'is_active'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean', 'is_active' => 'boolean'];
    }

    public function programme(): BelongsTo { return $this->belongsTo(Programme::class); }
    public function stages(): HasMany { return $this->hasMany(TemplateStage::class); }
}
