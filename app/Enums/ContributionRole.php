<?php

namespace App\Enums;

enum ContributionRole: string
{
    case Lead = 'lead';
    case Core = 'core';
    case Supporting = 'supporting';
}
