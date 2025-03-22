<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringCopyTest extends TestCase
{
    #[Test]
    public function stringCopy(): void
    {
        $string = Helper::stringCopy('abc', 1, 1);
        $this->assertTrue($string === 'b');

        $string = Helper::stringCopy('abc', 3);
        $this->assertTrue($string === '');

        $string = Helper::stringCopy('abc', -2);
        $this->assertTrue($string === 'bc');

        $string = Helper::stringCopy('abc', -2, 1);
        $this->assertTrue($string === 'b');

        $string = Helper::stringCopy(123, 1, 1);
        $this->assertTrue($string === '2');

        $string = Helper::stringCopy(123, -2);
        $this->assertTrue($string === '23');

        $string = Helper::stringCopy(123, -2, 1);
        $this->assertTrue($string === '2');
    }
}
