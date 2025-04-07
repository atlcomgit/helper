<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperDateTrait;

use Atlcom\Helper;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperDateTrait
 */
final class HelperDateFromStringTest extends TestCase
{
    #[Test]
    public function dateFromString(): void
    {
        $now = Carbon::today();

        // $date = Helper::dateFromString('');
        // $this->assertTrue($date === null);

        // $date = Helper::dateFromString('сегодня');
        // $this->assertEquals((clone $now)->format('d.m.Y'), $date?->format('d.m.Y'));

        // $date = Helper::dateFromString('завтра');
        // $this->assertEquals((clone $now)->addDay()->format('d.m.Y'), $date?->format('d.m.Y'));

        // $date = Helper::dateFromString('послезавтра');
        // $this->assertEquals((clone $now)->addDays(2)->format('d.m.Y'), $date?->format('d.m.Y'));

        // $date = Helper::dateFromString('вчера');
        // $this->assertEquals((clone $now)->subDay()->format('d.m.Y'), $date?->format('d.m.Y'));

        // $date = Helper::dateFromString('позавчера');
        // $this->assertEquals((clone $now)->subDays(2)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в этот понедельник');
        $this->assertEquals((clone $now)->weekday(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в следующий понедельник');
        $this->assertEquals((clone $now)->addWeek()->dayOfWeek(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в следующую пятницу');
        $this->assertEquals((clone $now)->addWeek()->dayOfWeek(5)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('на следующий день');
        $this->assertEquals((clone $now)->addDay()->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('на следующей неделе');
        $this->assertEquals((clone $now)->addWeek()->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в начале следующей неделе');
        $this->assertEquals((clone $now)->addWeek()->weekday(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в середине следующей неделе');
        $this->assertEquals((clone $now)->addWeek()->weekday(4)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в конце следующей неделе');
        $this->assertEquals((clone $now)->addWeek()->weekday(7)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в следующем месяце');
        $this->assertEquals((clone $now)->addMonth()->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в следующем году');
        $this->assertEquals((clone $now)->addYear()->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('через 1 день');
        $this->assertEquals((clone $now)->addDays(2)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('через 10 дней');
        $this->assertEquals((clone $now)->addDays(11)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('через 10 дней');
        $this->assertEquals((clone $now)->addDays(11)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('через 2 недели');
        $this->assertEquals((clone $now)->addWeeks(2)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('через 2 месяца');
        $this->assertEquals((clone $now)->addMonths(2)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('через 2 года');
        $this->assertEquals((clone $now)->addYears(2)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в начале следующей недели');
        $this->assertEquals((clone $now)->addWeek()->weekday(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в начале следующего месяца');
        $this->assertEquals((clone $now)->addMonth()->dayOfMonth(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('середина следующего месяца');
        $this->assertEquals(
            (clone $now)->addMonth()->dayOfMonth((int)((clone $now)->addMonth()->daysInMonth() / 2))->format('d.m.Y'),
            $date?->format('d.m.Y'),
        );

        $date = Helper::dateFromString('конец года');
        $this->assertEquals(
            (clone $now)->month(12)->dayOfMonth((clone $now)->month(12)->daysInMonth())->format('d.m.Y'),
            $date?->format('d.m.Y'),
        );

        $date = Helper::dateFromString('в начале следующего года');
        $this->assertEquals(
            (clone $now)->addYear()->month(1)->dayOfMonth(1)->month(1)->format('d.m.Y'),
            $date?->format('d.m.Y'),
        );

        $date = Helper::dateFromString('2 недели назад');
        $this->assertEquals((clone $now)->subWeeks(2)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('на прошлой неделе');
        $this->assertEquals((clone $now)->subWeeks(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('на позапрошлой неделе');
        $this->assertEquals((clone $now)->subWeeks(2)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в начале прошлой неделе');
        $this->assertEquals((clone $now)->subWeeks(1)->weekday(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в начале прошлого месяца');
        $this->assertEquals((clone $now)->subMonths(1)->dayOfMonth(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('в начале прошлого года');
        $this->assertEquals((clone $now)->subYears(1)->month(1)->dayOfMonth(1)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('25 числа');
        $this->assertEquals((clone $now)->day(25)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('2025-01-01');
        $this->assertEquals((clone $now)->year(2025)->month(01)->day(01)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('01.01.2025');
        $this->assertEquals((clone $now)->year(2025)->month(01)->day(01)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('25 числа следующего месяца');
        $this->assertEquals((clone $now)->addMonth()->day(25)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('25 октября 2025');
        $this->assertEquals((clone $now)->month(10)->day(25)->format('d.m.Y'), $date?->format('d.m.Y'));

        $date = Helper::dateFromString('Октябрь, 25 2025');
        $this->assertEquals((clone $now)->month(10)->day(25)->format('d.m.Y'), $date?->format('d.m.Y'));
    }
}
