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
final class HelperStringStartsTest extends TestCase
{
    #[Test]
    public function stringStartsTrue(): void
    {
        $string = Helper::stringStarts('abc', 'a');
        $this->assertTrue($string === 'a');

        $string = Helper::stringStarts('abc', ['a', 'b', 'c']);
        $this->assertTrue($string === 'a');
    }


    #[Test]
    public function stringStartsFalse(): void
    {
        $string = Helper::stringStarts('abc', 'b');
        $this->assertFalse($string === 'a');
        $this->assertTrue($string === null);

        $string = Helper::stringStarts('abc', ['b', 'c']);
        $this->assertFalse($string === 'a');
        $this->assertTrue($string === null);
    }
}
