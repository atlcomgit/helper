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
final class HelperCastToStringTest extends TestCase
{
    #[Test]
    public function castToString(): void
    {
        $this->assertSame('', Helper::castToString(''));

        $this->assertSame('0', Helper::castToString('0'));
        $this->assertSame('0', Helper::castToString(0));
        $this->assertSame('0', Helper::castToString(false));
        $this->assertSame('false', Helper::castToString('false'));

        $this->assertSame('1', Helper::castToString('1'));
        $this->assertSame('1', Helper::castToString(1));
        $this->assertSame('1', Helper::castToString(true));
        $this->assertSame('true', Helper::castToString('true'));


        $this->assertSame('[]', Helper::castToString([]));
        $this->assertSame('["a"]', Helper::castToString(['a']));
        $this->assertSame('{}', Helper::castToString((object)[]));

        $this->assertSame('1', Helper::castToString(static fn () => 1));

        $this->assertSame(null, Helper::castToString(null));
    }
}
