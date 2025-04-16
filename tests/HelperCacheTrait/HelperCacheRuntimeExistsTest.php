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
final class HelperCacheRuntimeExistsTest extends TestCase
{
    #[Test]
    public function cacheRuntimeExists(): void
    {
        Helper::cacheRuntimeClear();

        $boolean = Helper::cacheRuntimeExists('keyExists1');
        $this->assertTrue($boolean === false);

        Helper::cacheRuntimeSet('keyExists1', 1);
        $boolean = Helper::cacheRuntimeExists('keyExists1');
        $this->assertTrue($boolean === true);

        Helper::cacheRuntimeSet('keyExists2', fn () => 2);
        $boolean = Helper::cacheRuntimeExists('keyExists2');
        $this->assertTrue($boolean === true);

        Helper::cacheRuntimeSet(null, null);
        $boolean = Helper::cacheRuntimeExists(null);
        $this->assertTrue($boolean === true);
    }
}
