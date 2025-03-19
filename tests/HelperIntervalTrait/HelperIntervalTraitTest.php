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
final class HelperIntervalTraitTest extends TestCase
{
    #[Test]
    public function intervalBetween(): void
    {
        $bool = Helper::intervalBetween(2, 2);
        $this->assertTrue($bool);

        $bool = Helper::intervalBetween(2, 1, 2, 3);
        $this->assertTrue($bool);

        $bool = Helper::intervalBetween(2, 1, 3);
        $this->assertFalse($bool);

        $bool = Helper::intervalBetween(2, [1, 10]);
        $this->assertTrue($bool);

        $bool = Helper::intervalBetween(2, [1, 10], [11, 20]);
        $this->assertTrue($bool);

        $bool = Helper::intervalBetween(12, [1, 10], [11, 20]);
        $this->assertTrue($bool);

        $bool = Helper::intervalBetween(12, '1..10, 11..20');
        $this->assertTrue($bool);

        $bool = Helper::intervalBetween(12, '1..10, 13..20');
        $this->assertFalse($bool);

        $bool = Helper::intervalBetween('2025.01.02', '2025.01.01..2025.01.03');
        $this->assertTrue($bool);

        $bool = Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.02..2025.01.03');
        $this->assertTrue($bool);

        $bool = Helper::intervalBetween('2025.01.02', '2025.01.01, 2025.01.03..2025.01.04');
        $this->assertFalse($bool);

        $bool = Helper::intervalBetween('2025.01.02', ['2025.01.01', '2025.01.04']);
        $this->assertTrue($bool);
    }
}
