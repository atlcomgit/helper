<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperSizeTrait
 */
final class HelperSizeBytesToStringTest extends TestCase
{
    #[Test]
    public function sizeBytesToString(): void
    {
        $string = Helper::sizeBytesToString(1);
        $this->assertTrue($string === '1 байт');

        $string = Helper::sizeBytesToString(1000);
        $this->assertTrue($string === '1 Кб');

        $string = Helper::sizeBytesToString(1111);
        $this->assertTrue($string === '1.11 Кб');

        $string = Helper::sizeBytesToString(1000000);
        $this->assertTrue($string === '1 Мб');

        $string = Helper::sizeBytesToString(1111111);
        $this->assertTrue($string === '1.11 Мб');

        $string = Helper::sizeBytesToString(1000000000);
        $this->assertTrue($string === '1 Гб');

        $string = Helper::sizeBytesToString(1111111111);
        $this->assertTrue($string === '1.11 Гб');

        $string = Helper::sizeBytesToString(1000000000000);
        $this->assertTrue($string === '1 Тб');

        $string = Helper::sizeBytesToString(1111111111111);
        $this->assertTrue($string === '1.11 Тб');

        $string = Helper::sizeBytesToString(1000000000000000);
        $this->assertTrue($string === '1 Пб');

        $string = Helper::sizeBytesToString(1111111111111111);
        $this->assertTrue($string === '1.11 Пб');
    }
}
