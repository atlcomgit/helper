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
final class HelperArrayTrimValuesTest extends TestCase
{
    #[Test]
    public function arrayTrimValues(): void
    {
        $array = Helper::arrayTrimValues([' a ', ',b.,:;']);
        $this->assertSame(['a', 'b'], $array);

        $array = Helper::arrayTrimValues([' a ', ',b.,:;'], '');
        $this->assertSame([' a ', ',b.,:;'], $array);

        $array = Helper::arrayTrimValues(['a' => [' b ', ' c ']]);
        $this->assertSame(['a' => ['b', 'c']], $array);

        $array = Helper::arrayTrimValues(null);
        $this->assertEquals([], $array);
    }
}
