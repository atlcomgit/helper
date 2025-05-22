<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperVarTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperVarTrait
 */
final class HelperVarSwapTest extends TestCase
{
    #[Test]
    public function varSwap(): void
    {
        $integer1 = 1; $integer2 = 2;
        Helper::varSwap($integer1, $integer2);
        $this->assertSame($integer1, 2);
        $this->assertSame($integer2, 1);

        $float1 = 1.1; $float2 = 2.2;
        Helper::varSwap($float1, $float2);
        $this->assertSame($float1, 2.2);
        $this->assertSame($float2, 1.1);

        $string1 = '1'; $string2 = '2';
        Helper::varSwap($string1, $string2);
        $this->assertSame($string1, '2');
        $this->assertSame($string2, '1');

        $null1 = null; $null2 = null;
        Helper::varSwap($null1, $null2);
        $this->assertSame($null1, null);
        $this->assertSame($null2, null);

    }
}
