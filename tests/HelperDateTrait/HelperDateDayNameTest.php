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
final class HelperDateDayNameTest extends TestCase
{
    #[Test]
    public function dateDayName(): void
    {
        $string = Helper::dateDayName('');
        $this->assertTrue($string === '');

        $string = Helper::dateDayName(Carbon::parse('01.02.2025'));
        $this->assertEquals('суббота', $string);

        $string = Helper::dateDayName('31.12.2025');
        $this->assertEquals('среда', $string);

        $string = Helper::dateToString('31122025');
        $this->assertTrue($string === '');
    }
}
