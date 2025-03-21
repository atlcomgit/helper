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
final class HelperStringLengthTest extends TestCase
{
    #[Test]
    public function stringLength(): void
    {
        $integer = Helper::stringLength('123');
        $this->assertTrue($integer === 3);

        $integer = Helper::stringLength('');
        $this->assertTrue($integer === 0);
    }
}
