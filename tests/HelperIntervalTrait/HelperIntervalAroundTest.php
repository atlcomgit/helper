<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тесты трейта
 * @see \Atlcom\Traits\HelperIntervalTrait
 */
final class HelperIntervalAroundTest extends TestCase
{
    #[Test]
    public function intervalAround(): void
    {
        $integer = Helper::intervalAround(1 + 3, 1, 3);
        $this->assertEquals(1, $integer);

        $integer = Helper::intervalAround(3 + 1, 1, 3);
        $this->assertEquals(1, $integer);

        $integer = Helper::intervalAround(1 - 1, 1, 3);
        $this->assertEquals(3, $integer);
    }
}
