<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperNumberTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperNumberTrait
 */
final class HelperNumberFromStringTest extends TestCase
{
    #[Test]
    public function numberFromString(): void
    {
        $integer = Helper::numberFromString('сто двадцать три');
        $this->assertSame($integer, 123);

        $integer = Helper::numberFromString('сто двадцать три целых четыреста пятьдесят шесть тысячных');
        $this->assertSame($integer, 123.456);

        $integer = Helper::numberFromString('пять целых одна миллионная');
        $this->assertSame($integer, 5.000001);

        $integer = Helper::numberFromString('минус миллион один');
        $this->assertSame($integer, -1000001);

        $integer = Helper::numberFromString('-123');
        $this->assertSame($integer, -123);

        $integer = Helper::numberFromString(123.456);
        $this->assertSame($integer, 123.456);

        $integer = Helper::numberFromString('123,456');
        $this->assertSame($integer, 123.456);

        $integer = Helper::numberFromString('123.456,789');
        $this->assertSame($integer, 123.456);

        $integer = Helper::numberFromString('true');
        $this->assertSame($integer, 1);

        $integer = Helper::numberFromString('false');
        $this->assertSame($integer, 0);

        $integer = Helper::numberFromString('null');
        $this->assertSame($integer, 0);

        $integer = Helper::numberFromString(null);
        $this->assertTrue($integer === 0);
    }
}
