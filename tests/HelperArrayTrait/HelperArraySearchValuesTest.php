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
final class HelperArraySearchValuesTest extends TestCase
{
    #[Test]
    public function arraySearchValues(): void
    {
        $array = Helper::arraySearchValues(['a', 'b'], 0);
        $this->assertEquals([], $array);

        $array = Helper::arraySearchValues(['a', 'b'], 'a');
        $this->assertEquals(['a'], $array);

        $array = Helper::arraySearchValues(['a', 'b'], 'a', 'b');
        $this->assertEquals(['a', 'b'], $array);

        $array = Helper::arraySearchValues(['abc', 'def'], 'a*');
        $this->assertEquals(['abc'], $array);

        $array = Helper::arraySearchValues(['abc', 'def'], ['a*', '*f']);
        $this->assertEquals(['abc', 'def'], $array);

        $array = Helper::arraySearchValues(['a' => 'Иванов', 'b' => 'Иван'], 'Иван');
        $this->assertEquals(['b' => 'Иван'], $array);

        $array = Helper::arraySearchValues(['a' => 'Иванов', 'b' => 'Иван', 'c' => 'Иванович'], 'Иванов', 'Иван');
        $this->assertEquals(['a' => 'Иванов', 'b' => 'Иван'], $array);

        $array = Helper::arraySearchValues(['a' => 'Иванов', 'b' => 'Иван', 'c' => 'Иванович'], ['Иванов', 'Иван']);
        $this->assertEquals(['a' => 'Иванов', 'b' => 'Иван'], $array);

        $array = Helper::arraySearchValues([['a' => 'Иванов'], ['b' => 'Иван'], ['c' => 'Иванович']], ['Иванов', 'Иван']);
        $this->assertEquals([['a' => 'Иванов'], ['b' => 'Иван']], $array);

        $array = Helper::arraySearchValues([['a' => 'Иванов'], ['b' => 'Иван'], ['c' => 'Иванович']], ['Петров', 'Петр']);
        $this->assertEquals([], $array);

        $array = Helper::arraySearchValues([['a' => 'Иванов'], ['b' => 'Иван'], ['c' => 'Иванович']], ['*']);
        $this->assertEquals([['a' => 'Иванов'], ['b' => 'Иван'], ['c' => 'Иванович']], $array);

        $array = Helper::arraySearchValues([['a' => 'Иванов'], ['b' => 'Иван'], ['c' => 'Иванович']], ['/Иван*/']);
        $this->assertEquals([['a' => 'Иванов'], ['b' => 'Иван'], ['c' => 'Иванович']], $array);

        $array = Helper::arraySearchValues([['a' => 'Иванов'], ['b' => 'Иван'], ['c' => 'Иванович']], ['/Иванов*/']);
        $this->assertEquals([0 => ['a' => 'Иванов'], 2 => ['c' => 'Иванович']], $array);

        $array = Helper::arraySearchValues(null, null);
        $this->assertEquals([], $array);
    }
}
