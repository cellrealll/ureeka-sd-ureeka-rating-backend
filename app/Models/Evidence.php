<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasUuids;

    protected $table = 'evidence';

    public $timestamps = false;

    protected $fillable = [
        'submission_id',
        'kind',
        'label',
        'url',
        'file_key',
    ];
}