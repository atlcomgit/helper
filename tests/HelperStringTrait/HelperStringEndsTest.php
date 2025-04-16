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
final class HelperStringEndsTest extends TestCase
{
    #[Test]
    public function stringEndsTrue(): void
    {
        $string = Helper::stringEnds('abc', 'c');
        $this->assertTrue($string === 'c');

        $string = Helper::stringEnds('abc', ['a', 'b', 'c']);
        $this->assertTrue($string === 'c');

        $string = Helper::stringEnds(null, ['a']);
        $this->assertTrue($string === null);
    }


    #[Test]
    public function stringEndsFalse(): void
    {
        $string = Helper::stringEnds('abc', 'b');
        $this->assertFalse($string === 'c');
        $this->assertTrue($string === null);

        $string = Helper::stringEnds('abc', ['a', 'b']);
        $this->assertFalse($string === 'c');
        $this->assertTrue($string === null);
    }
}
