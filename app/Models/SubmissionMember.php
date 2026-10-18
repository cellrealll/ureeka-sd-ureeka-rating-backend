<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionMember extends Model
{
    protected $table = 'submission_members';

    public $timestamps = false;

    protected $fillable = [
        'submission_id',
        'user_id',
        'contribution',
    ];
}