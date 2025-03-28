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
final class HelperStringDeleteMultiplesTest extends TestCase
{
    #[Test]
    public function stringDeleteMultiples(): void
    {
        $string = Helper::stringDeleteMultiples('abc-abc', 'abc');
        $this->assertTrue($string === 'abc-abc');

        $string = Helper::stringDeleteMultiples('abcabc', 'abc');
        $this->assertTrue($string === 'abc');

        $string = Helper::stringDeleteMultiples('a--bb', ['-', 'b']);
        $this->assertTrue($string === 'a-b');

        $string = Helper::stringDeleteMultiples('абвабв', 'абв');
        $this->assertTrue($string === 'абв');

        $string = Helper::stringDeleteMultiples('абв--абв', ['абв', '-']);
        $this->assertTrue($string === 'абв-абв');

        $string = Helper::stringDeleteMultiples(112233, [1, 2, 3]);
        $this->assertTrue($string === '123');
    }
}