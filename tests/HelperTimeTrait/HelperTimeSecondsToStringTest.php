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
final class HelperTimeSecondsToStringTest extends TestCase
{
    #[Test]
    public function timeSecondsToString(): void
    {
        $string = Helper::timeSecondsToString(0);
        $this->assertEquals('0 секунд', $string);

        $string = Helper::timeSecondsToString(0.1);
        $this->assertEquals('0.1 секунды', $string);

        $string = Helper::timeSecondsToString(0.1, withMilliseconds: true);
        $this->assertEquals('100 миллисекунд', $string);

        $string = Helper::timeSecondsToString(0.01);
        $this->assertEquals('0 секунд', $string);

        $string = Helper::timeSecondsToString(0.01, withMilliseconds: true);
        $this->assertEquals('10 миллисекунд', $string);

        $string = Helper::timeSecondsToString(0.001);
        $this->assertEquals('0 секунд', $string);

        $string = Helper::timeSecondsToString(0.001, withMilliseconds: true);
        $this->assertEquals('1 миллисекунда', $string);

        $string = Helper::timeSecondsToString(0.22);
        $this->assertEquals('0.2 секунды', $string);

        $string = Helper::timeSecondsToString(0.22, withMilliseconds: true);
        $this->assertEquals('220 миллисекунд', $string);

        $string = Helper::timeSecondsToString(0.003544);
        $this->assertEquals('0 секунд', $string);

        $string = Helper::timeSecondsToString(0.003544, withMilliseconds: true);
        $this->assertEquals('3 миллисекунды', $string);

        $string = Helper::timeSecondsToString(0.0000003544, withMilliseconds: true);
        $this->assertEquals('0 миллисекунд', $string);

        $string = Helper::timeSecondsToString((int)0.1);
        $this->assertEquals('0 секунд', $string);

        $string = Helper::timeSecondsToString(0, withMilliseconds: true);
        $this->assertEquals('0 миллисекунд', $string);

        $string = Helper::timeSecondsToString(0.123, withMilliseconds: true);
        $this->assertEquals('123 миллисекунды', $string);

        $string = Helper::timeSecondsToString(1);
        $this->assertEquals('1 секунда', $string);

        $string = Helper::timeSecondsToString(0.0000000000001);
        $this->assertEquals('0 секунд', $string);

        $string = Helper::timeSecondsToString(1.0000000000001);
        $this->assertEquals('1 секунда', $string);

        $string = Helper::timeSecondsToString(123);
        $this->assertEquals('2 минуты 3 секунды', $string);

        $string = Helper::timeSecondsToString(3600);
        $this->assertEquals('1 час', $string);

        $string = Helper::timeSecondsToString(3600, withZero: true);
        $this->assertEquals('1 час 0 минут 0 секунд', $string);

        $string = Helper::timeSecondsToString(1.0, withZero: true, withMilliseconds: true);
        $this->assertEquals('1 секунда 0 миллисекунд', $string);

        $string = Helper::timeSecondsToString(60.0, withZero: true, withMilliseconds: true);
        $this->assertEquals('1 минута 0 секунд 0 миллисекунд', $string);

        $string = Helper::timeSecondsToString(value: 1.123, withMilliseconds: true);
        $this->assertEquals('1 секунда 123 миллисекунды', $string);

        $string = Helper::timeSecondsToString(1234567890.1234567890);
        $this->assertEquals('39 лет 53 дня 23 часа 31 минута 30 секунд', $string);

        $string = Helper::timeSecondsToString(1234567890.1234567890, withMilliseconds: true);
        $this->assertEquals('39 лет 53 дня 23 часа 31 минута 30 секунд 123 миллисекунды', $string);

        $string = Helper::timeSecondsToString(0.5);
        $this->assertEquals('0.5 секунды', $string);

        $string = Helper::timeSecondsToString(1.5);
        $this->assertEquals('1.5 секунды', $string);

        $string = Helper::timeSecondsToString(4.9);
        $this->assertEquals('4.9 секунды', $string);

        $string = Helper::timeSecondsToString(5);
        $this->assertEquals('5 секунд', $string);

        $string = Helper::timeSecondsToString(5.5);
        $this->assertEquals('5 секунд', $string);

        $string = Helper::timeSecondsToString(null);
        $this->assertEquals('', $string);
    }
}
