<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperArrayTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperArrayTrait
 */
final class HelperArrayDotTest extends TestCase
{
    #[Test]
    public function arrayDot(): void
    {
        $array = Helper::arrayDot(['a', 'b']);
        $this->assertEquals(['a', 'b'], $array);

        $array = Helper::arrayDot(['a' => 1, 'b' => 2]);
        $this->assertEquals(['a' => 1, 'b' => 2], $array);

        $array = Helper::arrayDot(['a' => ['b' => 2, 'c' => 3]]);
        $this->assertEquals(['a.b' => 2, 'a.c' => 3], $array);

        $array = Helper::arrayDot(['a' => ['b' => ['c' => 3, 'd' => 4]]]);
        $this->assertEquals(['a.b.c' => 3, 'a.b.d' => 4], $array);

        $array = Helper::arrayDot(['1', [2, 3, 4], 5]);
        $this->assertEquals(['0' => '1', '1.0' => 2, '1.1' => 3, '1.2' => 4, '2' => 5], $array);

        $array = Helper::arrayDot([]);
        $this->assertEquals([], $array);

        $array = Helper::arrayDot(null);
        $this->assertEquals([], $array);
    }
}