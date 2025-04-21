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
final class HelperNumberDecimalDigitsTest extends TestCase
{
    #[Test]
    public function numberDecimalDigits(): void
    {
        $integer = Helper::numberDecimalDigits(1.123);
        $this->assertSame($integer, 3);

        $integer = Helper::numberDecimalDigits('1.12345');
        $this->assertSame($integer, 5);

        $integer = Helper::numberDecimalDigits('1.0');
        $this->assertSame($integer, 0);

        $integer = Helper::numberDecimalDigits(null);
        $this->assertTrue($integer === 0);
    }
}
