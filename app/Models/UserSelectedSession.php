<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserSelectedSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'selectable_type',
        'selectable_id',
        'academic_year',
    ];

    /**
     * Get the user that owns this selection
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the selectable model (polymorphic)
     */
    public function selectable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by academic year
     */
    public function scopeByAcademicYear($query, string $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope to filter by current academic year
     */
    public function scopeCurrentYear($query)
    {
        return $query->where('academic_year', PDDay::getCurrentAcademicYear());
    }

    /**
     * Scope to filter by session type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('selectable_type', $type);
    }
}
