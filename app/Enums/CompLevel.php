<?php

namespace App\Enums;

enum CompLevel: string
{
    case Internal = 'internal';
    case Regional = 'regional';
    case National = 'national';
    case International = 'international';
}
