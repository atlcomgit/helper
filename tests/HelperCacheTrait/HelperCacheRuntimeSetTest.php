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
final class HelperCacheRuntimeSetTest extends TestCase
{
    #[Test]
    public function cacheRuntimeSet(): void
    {
        Helper::cacheRuntimeSet('key', 1);
        $this->assertTrue(true);
    }
}
