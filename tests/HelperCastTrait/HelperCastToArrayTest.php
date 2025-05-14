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
final class HelperCastToArrayTest extends TestCase
{
    #[Test]
    public function castToArray(): void
    {
        $this->assertSame([''], Helper::castToArray(''));

        $this->assertSame(['0'], Helper::castToArray('0'));
        $this->assertSame([0], Helper::castToArray(0));
        $this->assertSame([false], Helper::castToArray(false));
        $this->assertSame(['false'], Helper::castToArray('false'));

        $this->assertSame(['1'], Helper::castToArray('1'));
        $this->assertSame([1], Helper::castToArray(1));
        $this->assertSame([true], Helper::castToArray(true));
        $this->assertSame(['true'], Helper::castToArray('true'));


        $this->assertSame([], Helper::castToArray([]));
        $this->assertSame(['a'], Helper::castToArray('["a"]'));
        $this->assertSame(['a' => 1], Helper::castToArray('{"a": 1}'));
        $this->assertSame(['{"a"}'], Helper::castToArray('{"a"}'));
        $this->assertSame([], Helper::castToArray((object)[]));
        $this->assertSame([], Helper::castToArray('[]'));
        $this->assertSame([], Helper::castToArray('"[]"'));
        $this->assertSame([1], Helper::castToArray(' "[1]" '));

        $this->assertSame([1], Helper::castToArray(static fn () => 1));

        $this->assertSame(null, Helper::castToArray(null));
    }
}
