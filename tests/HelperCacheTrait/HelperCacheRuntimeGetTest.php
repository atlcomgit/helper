<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCacheTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тесты трейта
 * @see \Atlcom\Traits\HelperCacheTrait
 */
final class HelperCacheRuntimeGetTest extends TestCase
{
    #[Test]
    public function cacheRuntimeGet(): void
    {
        Helper::cacheRuntimeSet('key1', 1);
        $integer = Helper::cacheRuntimeGet('key1');
        $this->assertTrue($integer === 1);

        Helper::cacheRuntimeSet('key2', '2');
        $string = Helper::cacheRuntimeGet('key2');
        $this->assertTrue($string === '2');

        Helper::cacheRuntimeSet('key3', ['3']);
        $array = Helper::cacheRuntimeGet('key3');
        $this->assertTrue($array === ['3']);
    }
}
