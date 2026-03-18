<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisionComment extends Model
{
    protected $fillable = ['revision_id', 'user_id', 'content'];

    public function revision(): BelongsTo { return $this->belongsTo(Revision::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
