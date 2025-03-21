<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тесты трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringDeleteTest extends TestCase
{
    #[Test]
    public function stringDelete(): void
    {
        $string = Helper::stringDelete('abc', 1, 1);
        $this->assertTrue($string === 'ac');

        $string = Helper::stringDelete('abc', 1);
        $this->assertTrue($string === 'a');

        $string = Helper::stringDelete('abcd', 1, -1);
        $this->assertTrue($string === 'ad');

        $string = Helper::stringDelete('abc', -2);
        $this->assertTrue($string === 'a');

        $string = Helper::stringDelete(123, 1, 1);
        $this->assertTrue($string === '13');

        $string = Helper::stringDelete(1234, 1, -1);
        $this->assertTrue($string === '14');

        $string = Helper::stringDelete(123, -2);
        $this->assertTrue($string === '1');
    }
}
