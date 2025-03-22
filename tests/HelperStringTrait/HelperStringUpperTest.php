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
final class HelperStringUpperTest extends TestCase
{
    #[Test]
    public function stringUpper(): void
    {
        $string = Helper::stringUpper('abc');
        $this->assertTrue($string === 'ABC');

        $string = Helper::stringUpper('абв');
        $this->assertTrue($string === 'АБВ');
    }
}
