<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringConfig extends Model
{
    protected $table = 'scoring_config';

    protected $primaryKey = 'version';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'version',
        'is_active',
        'config',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'config' => 'array',
        ];
    }

    public static function active(): self
    {
        return self::query()
            ->where('is_active', true)
            ->firstOrFail();
    }
}