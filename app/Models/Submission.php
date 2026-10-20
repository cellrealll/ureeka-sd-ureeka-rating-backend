<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasUuids;

    protected $fillable = [
        'type',
        'status',
        'submitted_by',
        'title',
        'description',
        'achieved_on',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected function casts(): array
    {
        return [
            'achieved_on' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }

    public function members(): HasMany
    {
        return $this->hasMany(
            SubmissionMember::class,
            'submission_id'
        );
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function pointEntries(): HasMany
    {
        return $this->hasMany(PointEntry::class);
    }
}
