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
final class HelperArrayMappingKeysTest extends TestCase
{
    #[Test]
    public function arrayMappingKeys(): void
    {
        $array = Helper::arrayMappingKeys(['a', 'b'], 'a', 'c');
        $this->assertEquals(['a', 'b'], $array);

        $array = Helper::arrayMappingKeys(['a', 'b'], 0, 2);
        $this->assertEquals([1 => 'b', 2 => 'a'], $array);

        $array = Helper::arrayMappingKeys(['a', 'b'], [0 => 2]);
        $this->assertEquals([1 => 'b', 2 => 'a'], $array);

        $array = Helper::arrayMappingKeys(['a', 'b'], ['a' => 'c']);
        $this->assertEquals(['a', 'b'], $array);

        $array = Helper::arrayMappingKeys(['a' => 1, 'b' => 2], 'a', 'c');
        $this->assertEquals(['c' => 1, 'b' => 2], $array);

        $array = Helper::arrayMappingKeys(['a' => 1, 'b' => 2], ['a'], ['c']);
        $this->assertEquals(['c' => 1, 'b' => 2], $array);

        $array = Helper::arrayMappingKeys(['a' => 1, 'b' => 2], ['a' => 'c']);
        $this->assertEquals(['c' => 1, 'b' => 2], $array);

        $array = Helper::arrayMappingKeys([['a' => 1], ['a' => 2]], ['a' => 'c']);
        $this->assertEquals([['c' => 1], ['c' => 2]], $array);

        $array = Helper::arrayMappingKeys([
            ['a' => 1, 'b' => 2],
            ['a' => 3, 'b' => 4],
        ], '*.a', 'c');
        $this->assertEquals([
            ['c' => 1, 'b' => 2],
            ['c' => 3, 'b' => 4],
        ], $array);

        $array = Helper::arrayMappingKeys([
            ['a' => 1, 'b' => 2],
            ['a' => 3, 'b' => 4],
        ], ['0.a' => 'c', '*.b' => 'd']);
        $this->assertEquals([
            ['c' => 1, 'd' => 2],
            ['a' => 3, 'd' => 4],
        ], $array);

        $array = Helper::arrayMappingKeys([
            ['a' => 1, 'b' => 2],
            ['a' => 3, 'b' => 4],
        ], ['1.a' => 'c', '*.b' => 'd']);
        $this->assertEquals([
            ['a' => 1, 'd' => 2],
            ['c' => 3, 'd' => 4],
        ], $array);
    }
}
