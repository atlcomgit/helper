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
final class HelperColorHexToRgbTest extends TestCase
{
    #[Test]
    public function colorHexToRgb(): void
    {
        $array = Helper::colorHexToRgb('#000');
        $this->assertTrue($array === ['r' => 0, 'g' => 0, 'b' => 0, 'a' => null]);

        $array = Helper::colorHexToRgb('#000000');
        $this->assertTrue($array === ['r' => 0, 'g' => 0, 'b' => 0, 'a' => null]);

        $array = Helper::colorHexToRgb('#00000000');
        $this->assertTrue($array === ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);

        $array = Helper::colorHexToRgb('000');
        $this->assertTrue($array === ['r' => 0, 'g' => 0, 'b' => 0, 'a' => null]);

        $array = Helper::colorHexToRgb('0000');
        $this->assertTrue($array === []);

        $array = Helper::colorHexToRgb(null);
        $this->assertTrue($array === []);
    }
}
