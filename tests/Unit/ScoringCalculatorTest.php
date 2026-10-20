<?php

namespace Tests\Unit;

use App\Services\Scoring\ScoringCalculator;
use PHPUnit\Framework\TestCase;

class ScoringCalculatorTest extends TestCase
{
    public function test_competition_uses_the_first_matching_team_rule(): void
    {
        $config = ['competition' => [
            'base' => ['second' => 80],
            'level_multiplier' => ['national' => 1.5],
            'team_adjustment' => [
                ['max_size' => 1, 'pct' => 1.0],
                ['max_size' => 3, 'pct' => 0.8],
                ['max_size' => null, 'pct' => 0.6],
            ],
        ]];

        $calculator = new ScoringCalculator;

        $this->assertSame(120.0, $calculator->competition($config, 'second', 'national', 1));
        $this->assertSame(96.0, $calculator->competition($config, 'second', 'national', 3));
        $this->assertSame(72.0, $calculator->competition($config, 'second', 'national', 4));
    }

    public function test_product_caps_the_bonus_before_applying_the_role_adjustment(): void
    {
        $config = ['product' => [
            'base' => ['public_release' => 100],
            'impact_bonus' => ['adoption' => 30, 'maintenance' => 20],
            'impact_bonus_max' => 40,
            'role_adjustment' => ['core' => 0.8],
        ]];

        $calculator = new ScoringCalculator;

        $this->assertSame(80.0, $calculator->product($config, 'public_release', [], 'core'));
        $this->assertSame(104.0, $calculator->product($config, 'public_release', ['adoption'], 'core'));
        $this->assertSame(112.0, $calculator->product($config, 'public_release', ['adoption', 'maintenance', 'unknown'], 'core'));
    }

    public function test_challenge_returns_the_configured_points_as_a_float(): void
    {
        $this->assertSame(12.5, (new ScoringCalculator)->challenge([
            'challenge' => ['example_grade' => '12.5'],
        ], 'example_grade'));
    }
}
