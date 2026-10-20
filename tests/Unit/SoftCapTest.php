<?php

namespace Tests\Unit;

use App\Services\Scoring\SoftCap;
use PHPUnit\Framework\TestCase;

class SoftCapTest extends TestCase
{
    public function test_points_crossing_the_cap_are_partially_counted(): void
    {
        $results = SoftCap::apply([150, 120, 96, 80, 65], 400);

        $this->assertSame([150.0, 120.0, 96.0, 57.0, 32.5], $results);
        $this->assertSame(455.5, array_sum($results));
    }

    public function test_keys_and_input_order_are_preserved_at_the_cap_boundary(): void
    {
        $this->assertSame([
            'first' => 100.0,
            'second' => 10.0,
        ], SoftCap::apply(['first' => 100, 'second' => 40], 100, 0.25));
    }

    public function test_empty_input_and_zero_cap(): void
    {
        $this->assertSame([], SoftCap::apply([], 400));
        $this->assertSame([40.0], SoftCap::apply([80], 0));
    }
}
