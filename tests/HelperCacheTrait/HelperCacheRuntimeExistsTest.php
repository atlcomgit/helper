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
        $boolean = Helper::cacheRuntimeExists('keyX');
        $this->assertTrue($boolean === false);

        Helper::cacheRuntimeSet('keyX', fn () => 1);
        $boolean = Helper::cacheRuntimeExists('keyX');
        $this->assertTrue($boolean === true);
    }
}
