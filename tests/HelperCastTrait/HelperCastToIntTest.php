<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCastTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCastTrait
 */
final class HelperCastToIntTest extends TestCase
{
    #[Test]
    public function castInt(): void
    {
        $this->assertSame(123, Helper::castToInt(123));
        $this->assertSame(123, Helper::castToInt('123'));

        $this->assertSame(0, Helper::castToInt('0'));
        $this->assertSame(0, Helper::castToInt(0));
        $this->assertSame(0, Helper::castToInt(false));
        $this->assertSame(0, Helper::castToInt('false'));

        $this->assertSame(1, Helper::castToInt('1'));
        $this->assertSame(1, Helper::castToInt(1));
        $this->assertSame(1, Helper::castToInt(true));
        $this->assertSame(1, Helper::castToInt('true'));

        $this->assertSame(0, Helper::castToInt([]));
        $this->assertSame(1, Helper::castToInt(['a']));
        $this->assertSame(1, Helper::castToInt((object)[]));

        $this->assertSame(1, Helper::castToInt(static fn () => 1));

        $this->assertSame(null, Helper::castToInt(null));
    }
}
