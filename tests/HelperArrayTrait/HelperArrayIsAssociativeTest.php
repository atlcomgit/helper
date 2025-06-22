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
final class HelperArrayIsAssociativeTest extends TestCase
{
    #[Test]
    public function arrayIsAssociative(): void
    {
        $bool = Helper::arrayIsAssociative(['a', 'b']);
        $this->assertSame(false, $bool);

        $bool = Helper::arrayIsAssociative([1 => 'a', 2 => 'b']);
        $this->assertSame(true, $bool);

        $bool = Helper::arrayIsAssociative([0 => 'a', 2 => 'b']);
        $this->assertSame(true, $bool);

        $bool = Helper::arrayIsAssociative([0 => 'a', 1 => 'b']);
        $this->assertSame(false, $bool);

        $bool = Helper::arrayIsAssociative((object)['a' => 1]);
        $this->assertEquals(true, $bool);

        $bool = Helper::arrayIsAssociative(null);
        $this->assertEquals(false, $bool);
    }
}
