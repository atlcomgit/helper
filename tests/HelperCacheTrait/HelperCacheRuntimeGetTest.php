<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCacheTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCacheTrait
 */
final class HelperCacheRuntimeGetTest extends TestCase
{
    #[Test]
    public function cacheRuntimeGet(): void
    {
        Helper::cacheRuntimeClear();

        Helper::cacheRuntimeSet('keyGet1', 1);
        $integer = Helper::cacheRuntimeGet('keyGet1');
        $this->assertTrue($integer === 1);

        Helper::cacheRuntimeSet('keyGet2', '2');
        $string = Helper::cacheRuntimeGet('keyGet2');
        $this->assertTrue($string === '2');

        Helper::cacheRuntimeSet('keyGet3', ['3']);
        $array = Helper::cacheRuntimeGet('keyGet3');
        $this->assertTrue($array === ['3']);

        Helper::cacheRuntimeSet(null, 4);
        $array = Helper::cacheRuntimeGet(null);
        $this->assertTrue($array === 4);

        Helper::cacheRuntimeSet(null, null);
        $array = Helper::cacheRuntimeGet(null);
        $this->assertTrue($array === null);
    }
}
