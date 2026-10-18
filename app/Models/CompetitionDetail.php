<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionDetail extends Model
{
    protected $table = 'competition_details';

    protected $primaryKey = 'submission_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'submission_id',
        'competition_name',
        'organizer',
        'competition_url',
        'achievement',
        'level',
        'team_size',
    ];
}