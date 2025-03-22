<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperTimeTrait;

use Atlcom\Helper;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperTimeTrait
 */
final class HelperTimePeriodBetweenDatesToArrayTest extends TestCase
{
    #[Test]
    public function timePeriodBetweenDatesToArray(): void
    {
        $array = Helper::timePeriodBetweenDatesToArray(Carbon::parse('01.01.2025'), Carbon::parse('02.01.2025'));
        $this->assertEquals([
            'years' => 0,
            'months' => 0,
            'days' => 1,
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 0,
        ], $array);

        $array = Helper::timePeriodBetweenDatesToArray(
            Carbon::parse('01.01.2025 00:00:00'),
            Carbon::parse('02.02.2026 01:02:03'),
        );
        $this->assertEquals([
            'years' => 1,
            'months' => 1,
            'days' => 1,
            'hours' => 1,
            'minutes' => 2,
            'seconds' => 3,
        ], $array);

        $array = Helper::timePeriodBetweenDatesToArray('01.01.2025 00:00:00', '02.02.2026 01:02:03');
        $this->assertEquals([
            'years' => 1,
            'months' => 1,
            'days' => 1,
            'hours' => 1,
            'minutes' => 2,
            'seconds' => 3,
        ], $array);

        $array = Helper::timePeriodBetweenDatesToArray('28.02.2025', '01.03.2025');
        $this->assertEquals([
            'years' => 0,
            'months' => 0,
            'days' => 1,
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 0,
        ], $array);

        // Високосный год
        $array = Helper::timePeriodBetweenDatesToArray('28.02.2024', '01.03.2024');
        $this->assertEquals([
            'years' => 0,
            'months' => 0,
            'days' => 2,
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 0,
        ], $array);
    }
}