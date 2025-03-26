<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringSearchAllTest extends TestCase
{
    #[Test]
    public function stringSearchAll(): void
    {
        $array = Helper::stringSearchAll('abcd', 'bc', 'd');
        $this->assertTrue($array === ['bc', 'd']);

        $array = Helper::stringSearchAll('abcd', 'f');
        $this->assertTrue($array === []);

        $array = Helper::stringSearchAll('abcd', ['f', 'b', 'a']);
        $this->assertTrue($array === ['b', 'a']);

        $array = Helper::stringSearchAll('Иванов Иван Иванович', ['a' => 'Петр', 'b' => 'Иван', 'c' => 'Иванов']);
        $this->assertTrue($array === ['Иван', 'Иванов']);

        $string = Helper::stringSearchAll('Иванов Иван Иванович', '*Иван*');
        $this->assertTrue($string === ['*Иван*']);

        $string = Helper::stringSearchAll('Иванов Иван Иванович', '/Иван/');
        $this->assertTrue($string === ['/Иван/']);

        $string = Helper::stringSearchAll('Иванов Иван Иванович', ['*Петр*', '*Иван*']);
        $this->assertTrue($string === ['*Иван*']);

        $string = Helper::stringSearchAll('abc', 'd');
        $this->assertTrue($string === []);
    }
}
