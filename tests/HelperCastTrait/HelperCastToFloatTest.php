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
final class HelperCastToFloatTest extends TestCase
{
    #[Test]
    public function castToFloat(): void
    {
        $this->assertSame(123.456, Helper::castToFloat(123.456));
        $this->assertSame(123.456, Helper::castToFloat('123.456'));

        $this->assertSame(0.0, Helper::castToFloat('0'));
        $this->assertSame(0.0, Helper::castToFloat(0));
        $this->assertSame(0.0, Helper::castToFloat(false));
        $this->assertSame(0.0, Helper::castToFloat('false'));

        $this->assertSame(1.0, Helper::castToFloat('1'));
        $this->assertSame(1.0, Helper::castToFloat(1));
        $this->assertSame(1.0, Helper::castToFloat(true));
        $this->assertSame(1.0, Helper::castToFloat('true'));

        $this->assertSame(0.0, Helper::castToFloat([]));
        $this->assertSame(1.0, Helper::castToFloat(['a']));
        $this->assertSame(1.0, Helper::castToFloat((object)[]));

        $this->assertSame(1.0, Helper::castToFloat(static fn () => 1));

        $this->assertSame(0.0, Helper::castToFloat(''));
        $this->assertSame(null, Helper::castToFloat(null));
    }
}
