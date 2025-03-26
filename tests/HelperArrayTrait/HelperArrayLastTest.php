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
final class HelperArrayLastTest extends TestCase
{
    #[Test]
    public function arrayLast(): void
    {
        $mixed = Helper::arrayLast(['a', 'b']);
        $this->assertEquals('b', $mixed);

        $mixed = Helper::arrayLast(['a' => 1, 'b' => 2]);
        $this->assertEquals(2, $mixed);

        $mixed = Helper::arrayLast([]);
        $this->assertTrue($mixed === null);
    }
}
