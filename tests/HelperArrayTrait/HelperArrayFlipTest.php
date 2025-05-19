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
final class HelperArrayFlipTest extends TestCase
{
    #[Test]
    public function arrayFlip(): void
    {
        $array = Helper::arrayFlip(['a', 'b']);
        $this->assertSame(['a' => 0, 'b' => 1], $array);

        $array = Helper::arrayFlip(['a' => 'b']);
        $this->assertSame(['b' => 'a'], $array);

        $array = Helper::arrayFlip(['a', ['b', 'c']]);
        $this->assertSame(['a' => 0, 'b' => 0, 'c' => 1], $array);

        $array = Helper::arrayFlip(['a' => 'b', ['c' => 'd']]);
        $this->assertSame(['b' => 'a', 'd' => 'c'], $array);

        $array = Helper::arrayFlip(null);
        $this->assertEquals([], $array);
    }
}