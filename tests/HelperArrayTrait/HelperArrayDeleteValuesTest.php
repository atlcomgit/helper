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
final class HelperArrayDeleteValuesTest extends TestCase
{
    #[Test]
    public function arrayDeleteValues(): void
    {
        $array = Helper::arrayDeleteValues(['a', 'b'], 'a');
        $this->assertEquals([1 => 'b'], $array);

        $array = Helper::arrayDeleteValues(['a', 'b'], 'a', 'b');
        $this->assertEquals([], $array);

        $array = Helper::arrayDeleteValues(['a' => 1, 'b' => 2], 1);
        $this->assertEquals(['b' => 2], $array);

        $array = Helper::arrayDeleteValues(['a' => ['b' => 2, 'c' => 3]], 2, 3);
        $this->assertEquals(['a' => []], $array);

        $array = Helper::arrayDeleteValues(['a' => ['b' => 2, 'c' => 3]], 2);
        $this->assertEquals(['a' => ['c' => 3]], $array);

        $array = Helper::arrayDeleteValues(['a' => ['b' => 2, 'c' => 3]], [2, 3]);
        $this->assertEquals(['a' => []], $array);

        $array = Helper::arrayDeleteValues(['a' => ['b' => 2, 'c' => 3]], [3]);
        $this->assertEquals(['a' => ['b' => 2]], $array);

        $array = Helper::arrayDeleteValues(['abc' => 123, 'def' => 456], ['1*', '*6']);
        $this->assertEquals([], $array);

        $array = Helper::arrayDeleteValues(['a' => ['b' => 2, 'c' => 3]], ['*']);
        $this->assertEquals([], $array);

        $array = Helper::arrayDeleteValues(['a' => ['b' => 2, 'c' => 3]], ['/(2|3)/']);
        $this->assertEquals(['a' => []], $array);

        $array = Helper::arrayDeleteValues(['a' => ['b' => '2', 'c' => '3']], ['/2/i']);
        $this->assertEquals(['a' => ['c' => '3']], $array);

        $array = Helper::arrayDeleteValues(['a' => ['2', '3']], ['/2/i']);
        $this->assertEquals(['a' => [1 => '3']], $array);

        $array = Helper::arrayDeleteValues(['a', 'b'], [['/a/i']]);
        $this->assertEquals([1 => 'b'], $array);

        $array = Helper::arrayDeleteValues(null, null);
        $this->assertEquals([], $array);
    }


    #[Test]
    public function arrayDeleteValuesForHeaders(): void
    {
        $array = [
            'accept' => ["application/json, text/plain, */*"],
            'content-type' => ["application/json"],
            'user-agent' => ["axios/0.30.0"],
            'host' => ["skladno.local:8000"],
            'connection' => ["close"],
            'device' => ['sdf123'],
        ];
        $delete = ['/Authorization/i', '/Device/i', '*123'];

        $array = Helper::arrayDeleteValues($array, $delete ?? []);
        $this->assertEquals([
            'accept' => ['application/json, text/plain, */*'],
            'content-type' => ['application/json'],
            'user-agent' => ["axios/0.30.0"],
            'host' => ["skladno.local:8000"],
            'connection' => ["close"],
            'device' => [],
        ], $array);
    }
}
