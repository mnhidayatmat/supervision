<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiConversation extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'student_id', 'title', 'context_files', 'scope'];

    protected function casts(): array
    {
        return ['context_files' => 'array'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function messages(): HasMany { return $this->hasMany(AiMessage::class); }
}
