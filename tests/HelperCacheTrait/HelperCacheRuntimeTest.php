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
final class HelperCacheRuntimeTest extends TestCase
{
    #[Test]
    public function cacheRuntime(): void
    {
        Helper::cacheRuntimeClear();

        $mixed = Helper::cacheRuntime('keyRuntimeA', static fn () => 1);
        $mixed = Helper::cacheRuntimeGet('keyRuntimeA');
        $this->assertTrue($mixed === 1);
        $mixed = Helper::cacheRuntime('keyRuntimeA', static fn () => 0);
        $this->assertTrue($mixed === 1);

        $mixed = Helper::cacheRuntime('keyRuntimeB', static fn () => '2');
        $mixed = Helper::cacheRuntimeGet('keyRuntimeB');
        $this->assertTrue($mixed === '2');
        $mixed = Helper::cacheRuntime('keyRuntimeB', static fn () => 0);
        $this->assertTrue($mixed === '2');

        $mixed = Helper::cacheRuntime('keyRuntimeC', static fn () => ['3']);
        $mixed = Helper::cacheRuntimeGet('keyRuntimeC');
        $this->assertTrue($mixed === ['3']);
        $mixed = Helper::cacheRuntime('keyRuntimeC', static fn () => 0);
        $this->assertTrue($mixed === ['3']);

        $mixed = Helper::cacheRuntime('keyRuntimeD', static fn () => 1, false);
        $mixed = Helper::cacheRuntimeGet('keyRuntimeD');
        $this->assertTrue($mixed === null);

        $mixed = Helper::cacheRuntime(null, static fn () => 4);
        $mixed = Helper::cacheRuntimeGet(null);
        $this->assertTrue($mixed === 4);
        $mixed = Helper::cacheRuntime(null, static fn () => 0);
        $this->assertTrue($mixed === 4);
    }
}
