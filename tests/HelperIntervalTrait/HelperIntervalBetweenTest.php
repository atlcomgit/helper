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
final class HelperIntervalBetweenTest extends TestCase
{
    #[Test]
    public function intervalBetweenTrue(): void
    {
        $array = Helper::intervalBetween(2, 2);
        $this->assertEquals([2], $array);

        $array = Helper::intervalBetween(2, [1, null]);
        $this->assertEquals(['1..'], $array);

        $array = Helper::intervalBetween(2, [null, 3]);
        $this->assertEquals(['..3'], $array);

        $array = Helper::intervalBetween(2, 1, 2, 3);
        $this->assertEquals([2], $array);

        $array = Helper::intervalBetween(2, [1, 10]);
        $this->assertEquals(['1..10'], $array);

        $array = Helper::intervalBetween(2, [1, 10], [11, 20]);
        $this->assertEquals(['1..10'], $array);

        $array = Helper::intervalBetween(12, [1, 10], [11, 20]);
        $this->assertEquals(['11..20'], $array);

        $array = Helper::intervalBetween(12, '1..10, 11..20');
        $this->assertEquals(['11..20'], $array);

        $array = Helper::intervalBetween('2025.01.02', '2025.01.01..2025.01.03');
        $this->assertEquals(['2025.01.01..2025.01.03'], $array);

        $array = Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.02..2025.01.03');
        $this->assertEquals(['2025.01.02..2025.01.03'], $array);

        $array = Helper::intervalBetween('2025.01.02', ['2025.01.01', '2025.01.04']);
        $this->assertEquals(['2025.01.01..2025.01.04'], $array);

        $array = Helper::intervalBetween(2, [[1, 10]]);
        $this->assertEquals(['1..10'], $array);

        $array = Helper::intervalBetween(2, [[1, 10], [11, 20]]);
        $this->assertEquals(['1..10'], $array);

        $array = Helper::intervalBetween(2, '..');
        $this->assertEquals(['..'], $array);

        $array = Helper::intervalBetween(2, [null, null]);
        $this->assertEquals(['..'], $array);
    }


    #[Test]
    public function intervalBetweenFalse(): void
    {
        $array = Helper::intervalBetween(2, 1, 3);
        $this->assertEquals([], $array);

        $array = Helper::intervalBetween(12, '1..10, 13..20');
        $this->assertEquals([], $array);

        $array = Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.03..2025.01.04');
        $this->assertEquals([], $array);
    }
}
