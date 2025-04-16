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
final class HelperCacheRuntimeDeleteTest extends TestCase
{
    #[Test]
    public function cacheRuntimeDelete(): void
    {
        Helper::cacheRuntimeClear();

        Helper::cacheRuntimeSet('keyDelete1', 1);
        $boolean = Helper::cacheRuntimeExists('keyDelete1');
        $this->assertTrue($boolean === true);
        Helper::cacheRuntimeDelete('keyDelete1');
        $boolean = Helper::cacheRuntimeExists('keyDelete1');
        $this->assertTrue($boolean === false);

        Helper::cacheRuntimeSet(null, 2);
        $boolean = Helper::cacheRuntimeExists(null);
        $this->assertTrue($boolean === true);
        Helper::cacheRuntimeDelete(null);
        $boolean = Helper::cacheRuntimeExists(null);
        $this->assertTrue($boolean === false);
    }
}
