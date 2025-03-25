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
final class HelperArraySearchKeysAndValuesTest extends TestCase
{
    #[Test]
    public function arraySearchKeysAndValues(): void
    {
        $array = Helper::arraySearchKeysAndValues(['a', 'b'], 0, 'a');
        $this->assertEquals(['a'], $array);

        $array = Helper::arraySearchKeysAndValues(['a', 'b'], 0, 'c');
        $this->assertEquals([], $array);

        $array = Helper::arraySearchKeysAndValues(['a', 'b'], 1, 'b');
        $this->assertEquals([1 => 'b'], $array);

        $array = Helper::arraySearchKeysAndValues(['a', 'b'], 1, 'c');
        $this->assertEquals([], $array);

        $array = Helper::arraySearchKeysAndValues(['a', 'b'], [0], ['a']);
        $this->assertEquals(['a'], $array);

        $array = Helper::arraySearchKeysAndValues(['a' => 'Иванов', 'b' => 'Иван'], 'b', 'Иван');
        $this->assertEquals(['b' => 'Иван'], $array);

        $array = Helper::arraySearchKeysAndValues([
            'a' => 'Иванов',
            'b' => 'Иван',
            'c' => 'Иванович',
        ], [], ['Иванов', 'Иван']);
        $this->assertEquals([], $array);

        $array = Helper::arraySearchKeysAndValues([
            'a' => 'Иванов',
            'b' => 'Иван',
            'c' => 'Иванович',
        ], ['a', 'c'], ['Иванов', 'Иван']);
        $this->assertEquals(['a' => 'Иванов'], $array);

        $array = Helper::arraySearchKeysAndValues([
            'a' => 'Иванов',
            'b' => 'Иван',
            'c' => 'Иванович',
        ], ['a', 'b'], ['Иванов', 'Иван']);
        $this->assertEquals(['a' => 'Иванов', 'b' => 'Иван'], $array);

        $array = Helper::arraySearchKeysAndValues([
            'a' => 'Иванов',
            'b' => 'Иван',
            'c' => 'Иванович',
        ], ['*'], ['*']);
        $this->assertEquals(['a' => 'Иванов', 'b' => 'Иван', 'c' => 'Иванович'], $array);

        $array = Helper::arraySearchKeysAndValues([
            ['a' => 'Иванов'],
            ['b' => 'Иван'],
            ['c' => 'Иванович'],
        ], [], ['Иванов', 'Иван']);
        $this->assertEquals([], $array);

        $array = Helper::arraySearchKeysAndValues([
            ['a' => 'Иванов'],
            ['b' => 'Иван'],
            ['c' => 'Иванович'],
        ], ['0.a', '1.b'], ['Иванов', 'Иван']);
        $this->assertEquals(['0.a' => 'Иванов', '1.b' => 'Иван'], $array);

        $array = Helper::arraySearchKeysAndValues([
            ['a' => 'Иванов'],
            ['b' => 'Иван'],
            ['c' => 'Иванович'],
        ], ['*.a', '*.b'], ['Иванов', 'Иван']);
        $this->assertEquals(['0.a' => 'Иванов', '1.b' => 'Иван'], $array);

        $array = Helper::arraySearchKeysAndValues([
            ['a' => 'Иванов'],
            ['b' => 'Иван'],
            ['c' => 'Иванович'],
        ], ['*.a', '*.b'], ['Петров', 'Петр']);
        $this->assertEquals([], $array);

        $array = Helper::arraySearchKeysAndValues([
            ['a' => 'Иванов'],
            ['b' => 'Иван'],
            ['c' => 'Иванович'],
        ], ['*'], ['*']);
        $this->assertEquals(['0.a' => 'Иванов', '1.b' => 'Иван', '2.c' => 'Иванович'], $array);

        $array = Helper::arraySearchKeysAndValues([
            ['a' => 'Иванов'],
            ['b' => 'Иван'],
            ['c' => 'Иванович'],
        ], ['/(a|b|c)/'], ['/Иванов/']);
        $this->assertEquals(['0.a' => 'Иванов', '2.c' => 'Иванович'], $array);
    }
}
