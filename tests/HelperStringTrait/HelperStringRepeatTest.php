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
final class HelperStringRepeatTest extends TestCase
{
    #[Test]
    public function stringRepeat(): void
    {
        $string = Helper::stringRepeat('a', 3);
        $this->assertTrue($string === 'aaa');

        $string = Helper::stringRepeat('abc', 2);
        $this->assertTrue($string === 'abcabc');

        $string = Helper::stringRepeat(1, 3);
        $this->assertTrue($string === '111');
    }
}
