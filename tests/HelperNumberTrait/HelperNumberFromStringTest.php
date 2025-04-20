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
        // $integer = Helper::numberFromString('сто двадцать три');
        // $this->assertTrue($integer === 123);

        $integer = Helper::numberFromString('сто двадцать три целых четыреста пятьдесят шесть тысячных');
        $this->assertEquals($integer, 123.456);
        $this->assertTrue($integer === 123.456);

        $integer = Helper::numberFromString(null);
        $this->assertEquals($integer, 0);
        $this->assertTrue($integer === 0);
    }
}
