<?php

namespace App\Enums;

enum AchievementLevel: string
{
    case Champion = 'champion';
    case Second = 'second';
    case Third = 'third';
    case Finalist = 'finalist';
    case Qualified = 'qualified';
    case Participant = 'participant';
}
