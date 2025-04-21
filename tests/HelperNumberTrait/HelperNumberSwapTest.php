<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperNumberTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperNumberTrait
 */
final class HelperNumberSwapTest extends TestCase
{
    #[Test]
    public function numberSwap(): void
    {
        $integer1 = 1; $integer2 = 2;
        Helper::numberSwap($integer1, $integer2);
        $this->assertSame($integer1, 2);
        $this->assertSame($integer2, 1);

        $float1 = 1.1; $float2 = 2.2;
        Helper::numberSwap($float1, $float2);
        $this->assertSame($float1, 2.2);
        $this->assertSame($float2, 1.1);

        $string1 = '1'; $string2 = '2';
        Helper::numberSwap($string1, $string2);
        $this->assertSame($string1, '2');
        $this->assertSame($string2, '1');

        $null1 = null; $null2 = null;
        Helper::numberSwap($null1, $null2);
        $this->assertSame($null1, null);
        $this->assertSame($null2, null);

    }
}
