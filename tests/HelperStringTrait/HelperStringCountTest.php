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
final class HelperStringCountTest extends TestCase
{
    #[Test]
    public function stringCount(): void
    {
        $integer = Helper::stringCount('abc', 'a');
        $this->assertTrue($integer === 1);

        $integer = Helper::stringCount('abc', 'a', 'b');
        $this->assertTrue($integer === 2);

        $integer = Helper::stringCount('abc', ['a', 'b']);
        $this->assertTrue($integer === 2);

        $integer = Helper::stringCount('абв', 'а');
        $this->assertTrue($integer === 1);
    }
}
