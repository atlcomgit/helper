<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperTimeTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperTimeTrait
 */
final class HelperTimeSecondsToArrayTest extends TestCase
{
    #[Test]
    public function timeSecondsToArray(): void
    {
        $array = Helper::timeSecondsToArray(1);
        $this->assertEquals([
            'years' => 0,
            'days' => 0,
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 1,
            'milliseconds' => 0,
        ], $array);

        $array = Helper::timeSecondsToArray(12.345);
        $this->assertEquals([
            'years' => 0,
            'days' => 0,
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 12,
            'milliseconds' => 345,
        ], $array);

        $array = Helper::timeSecondsToArray(1234567890.1234567890);
        $this->assertEquals([
            'years' => 39,
            'days' => 53,
            'hours' => 23,
            'minutes' => 31,
            'seconds' => 30,
            'milliseconds' => 123,
        ], $array);
    }
}
