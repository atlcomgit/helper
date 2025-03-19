<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperString;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тесты трейта HelperStringTrait
 */
final class InIntervalTest extends TestCase
{
    #[Test]
    public function example(): void
    {
        $this->assertTrue(Helper::inInterval(2, 2));
        $this->assertTrue(Helper::inInterval(2, 1, 2, 3));
        $this->assertFalse(Helper::inInterval(2, 1, 3));

        $this->assertTrue(Helper::inInterval(2, [1, 10]));
        $this->assertTrue(Helper::inInterval(2, [1, 10], [11, 20]));
        $this->assertTrue(Helper::inInterval(12, [1, 10], [11, 20]));
        $this->assertTrue(Helper::inInterval(12, '1..10, 11..20'));
        $this->assertFalse(Helper::inInterval(12, '1..10, 13..20'));

        $this->assertTrue(Helper::inInterval('2025.01.02', '2025.01.01..2025.01.03'));
        $this->assertTrue(Helper::inInterval('2025.01.02', '2025.01.01, 2025.01.02, 2025.01.03'));
        $this->assertFalse(Helper::inInterval('2025.01.02', '2025.01.01, 2025.01.03, 2025.01.04'));
    }
}