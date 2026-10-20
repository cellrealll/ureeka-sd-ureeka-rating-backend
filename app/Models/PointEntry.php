<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointEntry extends Model
{
    use HasUuids;

    protected $fillable = [
        'submission_id',
        'user_id',
        'category',
        'season_year',
        'achieved_on',
        'raw_points',
        'counted_points',
        'breakdown',
        'revoked_at',
        'revoked_reason',
    ];

    protected function casts(): array
    {
        return [
            'achieved_on' => 'date',
            'raw_points' => 'float',
            'counted_points' => 'float',
            'breakdown' => 'array',
            'revoked_at' => 'datetime',
        ];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
