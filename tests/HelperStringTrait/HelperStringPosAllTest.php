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
final class HelperStringPosAllTest extends TestCase
{
    #[Test]
    public function stringPosAll(): void
    {
        $array = Helper::stringPosAll('abcd', 'bc', 'd');
        $this->assertTrue($array === ['bc' => 1, 'd' => 3]);

        $array = Helper::stringPosAll('abcd', 'f');
        $this->assertTrue($array === []);

        $array = Helper::stringPosAll('abcd a', ['f', 'b', 'a']);
        $this->assertTrue($array === ['b' => 1, 'a' => [0, 5]]);

        $array = Helper::stringPosAll('abcd ad', ['f', 'b', 'a', 'other' => ['c', 'd']]);
        $this->assertTrue($array === ['b' => 1, 'a' => [0, 5], 'c' => 2, 'd' => [3, 6]]);

        $array = Helper::stringPosAll('Иванов Иван Иванович и Петр', [['a' => 'Петр', 'b' => 'Иван', 'c' => 'Иванов']]);
        $this->assertTrue($array === ['Петр' => 23, 'Иван' => [0, 7, 12], 'Иванов' => [0, 12]]);
    }
}
