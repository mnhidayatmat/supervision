<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    protected $fillable = ['student_id', 'parent_id', 'name', 'path', 'category'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function parent(): BelongsTo { return $this->belongsTo(Folder::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(Folder::class, 'parent_id'); }
    public function files(): HasMany { return $this->hasMany(File::class); }
}
