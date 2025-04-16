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
final class HelperArrayDeleteKeysTest extends TestCase
{
    #[Test]
    public function arrayDeleteKeys(): void
    {
        $array = Helper::arrayDeleteKeys(['a', 'b'], 0);
        $this->assertEquals([1 => 'b'], $array);

        $array = Helper::arrayDeleteKeys(['a', 'b'], 0, 1);
        $this->assertEquals([], $array);

        $array = Helper::arrayDeleteKeys(['a' => 1, 'b' => 2], 'a');
        $this->assertEquals(['b' => 2], $array);

        $array = Helper::arrayDeleteKeys([['b' => 2, 'c' => 3]], '0.b');
        $this->assertEquals([['c' => 3]], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], 'a');
        $this->assertEquals([], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], 'b');
        $this->assertEquals(['a' => ['b' => 2, 'c' => 3]], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], 'a.b');
        $this->assertEquals(['a' => ['c' => 3]], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], '*.b');
        $this->assertEquals(['a' => ['c' => 3]], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], ['a.b']);
        $this->assertEquals(['a' => ['c' => 3]], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], ['a.b', 'a.c']);
        $this->assertEquals(['a' => []], $array);

        $array = Helper::arrayDeleteKeys(['abc' => 1, 'def' => 2], ['a*', '*f']);
        $this->assertEquals([], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], ['b', 'c']);
        $this->assertEquals(['a' => ['b' => 2, 'c' => 3]], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], ['*']);
        $this->assertEquals([], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], ['/(a|b)/']);
        $this->assertEquals([], $array);

        $array = Helper::arrayDeleteKeys(['a' => ['b' => 2, 'c' => 3]], ['/(a\.b|a\.c)/']);
        $this->assertEquals(['a' => []], $array);

        $array = Helper::arrayDeleteKeys(null, null);
        $this->assertEquals([], $array);
    }
}
