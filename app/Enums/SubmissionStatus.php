<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Pending = 'pending';
    case RevisionRequested = 'revision_requested';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Withdrawn = 'withdrawn';
}
