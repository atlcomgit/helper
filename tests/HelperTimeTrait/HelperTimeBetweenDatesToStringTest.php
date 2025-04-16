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
final class HelperTimeBetweenDatesToStringTest extends TestCase
{
    #[Test]
    public function timeBetweenDatesToString(): void
    {
        $string = Helper::timeBetweenDatesToString(Carbon::parse('01.01.2025'), Carbon::parse('02.01.2025'));
        $this->assertEquals('1 день', $string);

        $string = Helper::timeBetweenDatesToString(
            Carbon::parse('01.01.2025 00:00:00'),
            Carbon::parse('02.02.2026 01:02:03'),
        );
        $this->assertEquals('1 год 1 месяц 1 день', $string);

        $string = Helper::timeBetweenDatesToString('01.01.2025 00:00:00', '02.02.2026 01:02:03');
        $this->assertEquals('1 год 1 месяц 1 день', $string);

        $string = Helper::timeBetweenDatesToString('01.01.2025 00:00:00', '02.02.2026 01:02:03', true);
        $this->assertEquals('1 год 1 месяц 1 день 1 час 2 минуты 3 секунды', $string);

        $string = Helper::timeBetweenDatesToString('01.01.2025 00:00:00', '02.02.2026 01:02:03', true, true);
        $this->assertEquals('1 год 1 месяц 1 сутки 1 час 2 минуты 3 секунды', $string);

        $string = Helper::timeBetweenDatesToString(null, null);
        $this->assertEquals('0 дней', $string);

        $string = Helper::timeBetweenDatesToString(null, null, true);
        $this->assertEquals('0 дней', $string);

        $string = Helper::timeBetweenDatesToString(null, null, true, true);
        $this->assertEquals('0 суток', $string);
    }
}
