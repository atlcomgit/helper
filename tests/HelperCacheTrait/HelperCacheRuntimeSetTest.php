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
final class HelperCacheRuntimeSetTest extends TestCase
{
    #[Test]
    public function cacheRuntimeSet(): void
    {
        Helper::cacheRuntimeClear();

        Helper::cacheRuntimeSet('keySet1', 1);
        $boolean = Helper::cacheRuntimeExists('keySet1');
        $this->assertTrue($boolean === true);

        Helper::cacheRuntimeSet(null, null);
        $boolean = Helper::cacheRuntimeExists(null);
        $this->assertTrue($boolean === true);
    }
}
