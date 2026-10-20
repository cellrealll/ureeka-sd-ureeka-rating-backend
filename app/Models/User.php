<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'supabase_id',
        'email',
        'name',
        'nim',
        'avatar_url',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'supabase_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(
            Submission::class,
            'submitted_by'
        );
    }

    public function pointEntries(): HasMany
    {
        return $this->hasMany(PointEntry::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
