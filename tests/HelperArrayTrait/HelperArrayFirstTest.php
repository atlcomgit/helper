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
final class HelperArrayFirstTest extends TestCase
{
    #[Test]
    public function arrayFirst(): void
    {
        $mixed = Helper::arrayFirst(['a', 'b']);
        $this->assertEquals('a', $mixed);

        $mixed = Helper::arrayFirst(['a' => 1, 'b' => 2]);
        $this->assertEquals(1, $mixed);

        $mixed = Helper::arrayLast([]);
        $this->assertTrue($mixed === null);
    }
}
