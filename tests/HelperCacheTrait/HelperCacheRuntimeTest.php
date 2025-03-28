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
        $mixed = Helper::cacheRuntime('keyA', fn () => 1);
        $mixed = Helper::cacheRuntimeGet('keyA');
        $this->assertTrue($mixed === 1);

        $mixed = Helper::cacheRuntime('keyA', fn () => 0);
        $this->assertTrue($mixed === 1);

        $mixed = Helper::cacheRuntime('keyB', fn () => '2');
        $mixed = Helper::cacheRuntimeGet('keyB');
        $this->assertTrue($mixed === '2');

        $mixed = Helper::cacheRuntime('keyB', fn () => 0);
        $this->assertTrue($mixed === '2');

        $mixed = Helper::cacheRuntime('keyC', fn () => ['3']);
        $mixed = Helper::cacheRuntimeGet('keyC');
        $this->assertTrue($mixed === ['3']);

        $mixed = Helper::cacheRuntime('keyC', fn () => 0);
        $this->assertTrue($mixed === ['3']);
    }
}
