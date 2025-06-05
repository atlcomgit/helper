<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringSplitSearchTest extends TestCase
{
    #[Test]
    public function stringSplitSearch(): void
    {
        $array = Helper::stringSplitSearch('abc,def', ',', 'a');
        $this->assertSame(['a' => [0]], $array);

        $array = Helper::stringSplitSearch('abc,def', ',', 'a', 'd');
        $this->assertSame(['a' => [0], 'd' => [1]], $array);

        $array = Helper::stringSplitSearch('abc,def', ',', 'a*', '*e*');
        $this->assertSame(['a*' => [0], '*e*' => [1]], $array);

        $array = Helper::stringSplitSearch('abc,def,xyz', ',', []);
        $this->assertSame([], $array);

        $array = Helper::stringSplitSearch(',abc,def,xyz,', ',', null);
        $this->assertSame([], $array);

        $array = Helper::stringSplitSearch(',abcx,def/xyz,', [',', '/'], 'x');
        $this->assertSame(['x' => [1, 3]], $array);

        $array = Helper::stringSplitSearch(null, null, null);
        $this->assertSame([], $array);
    }
}