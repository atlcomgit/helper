<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperHashTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperHashTrait
 */
final class HelperHashXxh128Test extends TestCase
{
    #[Test]
    public function hashXxh128(): void
    {
        $string = Helper::hashXxh128('abc');
        $this->assertTrue($string === '06b05ab6733a618578af5f94892f3950');
    }
}
