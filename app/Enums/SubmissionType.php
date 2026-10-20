<?php

namespace App\Enums;

enum SubmissionType: string
{
    case Competition = 'competition';
    case Product = 'product';
    case Challenge = 'challenge';
}
