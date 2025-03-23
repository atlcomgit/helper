<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperColorTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperColorTrait
 */
final class HelperColorRgbToHexTest extends TestCase
{
    #[Test]
    public function colorRgbToHex(): void
    {
        $string = Helper::colorRgbToHex(0, 0, 0);
        $this->assertTrue($string === '#000000');

        $string = Helper::colorRgbToHex(0, 0, 0, 0);
        $this->assertTrue($string === '#00000000');
    }
}
