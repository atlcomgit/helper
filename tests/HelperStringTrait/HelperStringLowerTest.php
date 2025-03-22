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
final class HelperStringLowerTest extends TestCase
{
    #[Test]
    public function stringLower(): void
    {
        $string = Helper::stringLower('ABC');
        $this->assertTrue($string === 'abc');

        $string = Helper::stringLower('АБВ');
        $this->assertTrue($string === 'абв');
    }
}
