<?php

namespace App\Services\Scoring;

class ScoringCalculator
{
    public function competition(
        array $config,
        string $achievement,
        string $level,
        int $teamSize
    ): float {
        $base = $config['competition']['base'][$achievement];
        $multiplier =
            $config['competition']['level_multiplier'][$level];

        $adjustment = 0.6;

        foreach ($config['competition']['team_adjustment'] as $rule) {
            if (
                $rule['max_size'] === null ||
                $teamSize <= $rule['max_size']
            ) {
                $adjustment = $rule['pct'];
                break;
            }
        }

        return round(
            $base * $multiplier * $adjustment,
            2
        );
    }

    public function product(
        array $config,
        string $productType,
        array $impactFlags,
        string $role
    ): float {
        $base = $config['product']['base'][$productType];

        $bonus = 0;

        foreach ($impactFlags as $flag) {
            $bonus += $config['product']['impact_bonus'][$flag] ?? 0;
        }

        $bonus = min(
            $bonus,
            $config['product']['impact_bonus_max']
        );

        $roleAdjustment =
            $config['product']['role_adjustment'][$role];

        return round(
            ($base + $bonus) * $roleAdjustment,
            2
        );
    }

    public function challenge(
        array $config,
        string $grade
    ): float {
        return (float) $config['challenge'][$grade];
    }
}
