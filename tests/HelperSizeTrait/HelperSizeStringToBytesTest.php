<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperSizeTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperSizeTrait
 */
final class HelperSizeStringToBytesTest extends TestCase
{
    #[Test]
    public function sizeStringToBytesTrue(): void
    {
        $integer = Helper::sizeStringToBytes('1 байт');
        $this->assertTrue($integer === 1);

        $integer = Helper::sizeStringToBytes('1 Кб');
        $this->assertTrue($integer === 1000);

        $integer = Helper::sizeStringToBytes('1 Килобайт');
        $this->assertTrue($integer === 1000);

        $integer = Helper::sizeStringToBytes('1 Мб');
        $this->assertTrue($integer === 1000000);

        $integer = Helper::sizeStringToBytes('1 Мегабайт');
        $this->assertTrue($integer === 1000000);

        $integer = Helper::sizeStringToBytes('1 Гб');
        $this->assertTrue($integer === 1000000000);

        $integer = Helper::sizeStringToBytes('1 Гигабайт');
        $this->assertTrue($integer === 1000000000);

        $integer = Helper::sizeStringToBytes('1 Тб');
        $this->assertTrue($integer === 1000000000000);

        $integer = Helper::sizeStringToBytes('1 Терабайт');
        $this->assertTrue($integer === 1000000000000);

        $integer = Helper::sizeStringToBytes('1 Пб');
        $this->assertTrue($integer === 1000000000000000);

        $integer = Helper::sizeStringToBytes('1 Петабайт');
        $this->assertTrue($integer === 1000000000000000);
    }


    #[Test]
    public function sizeStringToBytesFalse(): void
    {
        $integer = Helper::sizeStringToBytes('1');
        $this->assertTrue($integer === 0);
    }
}