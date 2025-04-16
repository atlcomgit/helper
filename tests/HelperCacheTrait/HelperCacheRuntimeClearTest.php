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
final class HelperCacheRuntimeClearTest extends TestCase
{
    #[Test]
    public function cacheRuntimeClear(): void
    {
        Helper::cacheRuntimeClear();

        $boolean = Helper::cacheRuntimeSet('keyClear1', 1);
        $boolean = Helper::cacheRuntimeExists('keyClear1');
        $this->assertTrue($boolean === true);

        $boolean = Helper::cacheRuntimeSet('keyClear2', 2);
        $boolean = Helper::cacheRuntimeExists('keyClear2');
        $this->assertTrue($boolean === true);

        Helper::cacheRuntimeClear();

        $boolean = Helper::cacheRuntimeExists('keyClear1');
        $this->assertTrue($boolean === false);

        $boolean = Helper::cacheRuntimeExists('keyClear2');
        $this->assertTrue($boolean === false);
    }
}
