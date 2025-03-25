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
final class HelperArraySearchKeysTest extends TestCase
{
    #[Test]
    public function arraySearchKeys(): void
    {
        $array = Helper::arraySearchKeys(['a', 'b'], 0);
        $this->assertEquals(['a'], $array);

        $array = Helper::arraySearchKeys(['a', 'b'], 0, 1);
        $this->assertEquals(['a', 'b'], $array);

        $array = Helper::arraySearchKeys(['a' => 1, 'b' => 2], 'a');
        $this->assertEquals(['a' => 1], $array);

        $array = Helper::arraySearchKeys([['b' => 2, 'c' => 3]], '0.b');
        $this->assertEquals(['0.b' => 2], $array);

        $array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], 'a.b');
        $this->assertEquals(['a.b' => 2], $array);

        $array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], '*.b');
        $this->assertEquals(['a.b' => 2], $array);

        $array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], ['a.b']);
        $this->assertEquals(['a.b' => 2], $array);

        $array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], ['a.b', 'a.c']);
        $this->assertEquals(['a.b' => 2, 'a.c' => 3], $array);

        $array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], ['b', 'c']);
        $this->assertEquals([], $array);

        $array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], ['*']);
        $this->assertEquals(['a.b' => 2, 'a.c' => 3], $array);

        $array = Helper::arraySearchKeys(['a' => ['b' => 2, 'c' => 3]], ['/(a|b)/']);
        $this->assertEquals(['a.b' => 2, 'a.c' => 3], $array);
    }
}
