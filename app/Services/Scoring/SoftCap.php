<?php

namespace App\Services\Scoring;

class SoftCap
{
    public static function apply(
        array $rawPoints,
        float $cap,
        float $overPct = 0.5
    ): array {
        $counted = [];
        $running = 0.0;

        foreach ($rawPoints as $index => $points) {
            $withinCap = max(
                0.0,
                min($points, $cap - $running)
            );

            $exceeding = $points - $withinCap;

            $counted[$index] = round(
                $withinCap + ($exceeding * $overPct),
                2
            );

            $running += $points;
        }

        return $counted;
    }
}
