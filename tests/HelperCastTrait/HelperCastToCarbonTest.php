<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCastTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCastTrait
 */
final class HelperCastToCarbonTest extends TestCase
{
    #[Test]
    public function castToCarbonDates(): void
    {
        // date only
        $this->assertSame('2025-08-08', Helper::castToCarbon('2025-08-08')?->format('Y-m-d'));
        $this->assertSame('2025-08-08', Helper::castToCarbon('08-08-2025')?->format('Y-m-d'));
        $this->assertSame('2025-08-08', Helper::castToCarbon('2025.08.08')?->format('Y-m-d'));
        $this->assertSame('2025-08-08', Helper::castToCarbon('08.08.2025')?->format('Y-m-d'));
        $this->assertSame('2025-08-08', Helper::castToCarbon('2025/08/08')?->format('Y-m-d'));
        $this->assertSame('2025-08-08', Helper::castToCarbon('08/08/2025')?->format('Y-m-d'));
    }


    #[Test]
    public function castToCarbonDateTimes(): void
    {
        $this->assertSame('2025-08-08 12:34:56', Helper::castToCarbon('2025-08-08 12:34:56')?->format('Y-m-d H:i:s'));
        $this->assertSame('2025-08-08 12:34:56', Helper::castToCarbon('08-08-2025 12:34:56')?->format('Y-m-d H:i:s'));
        $this->assertSame('2025-08-08 12:34:56', Helper::castToCarbon('2025.08.08 12:34:56')?->format('Y-m-d H:i:s'));
        $this->assertSame('2025-08-08 12:34:56', Helper::castToCarbon('08.08.2025 12:34:56')?->format('Y-m-d H:i:s'));
        $this->assertSame('2025-08-08 12:34:56', Helper::castToCarbon('2025/08/08 12:34:56')?->format('Y-m-d H:i:s'));
        $this->assertSame('2025-08-08 12:34:56', Helper::castToCarbon('08/08/2025 12:34:56')?->format('Y-m-d H:i:s'));
    }


    #[Test]
    public function castToCarbonTimestamps(): void
    {
        // integer seconds timestamp (UTC reference)
        $ts = strtotime('2025-08-08 00:00:00 UTC');
        $this->assertSame('2025-08-08 00:00:00', Helper::castToCarbon($ts)?->setTimezone('UTC')->format('Y-m-d H:i:s'));

        // float milliseconds timestamp
        $ms = (float)($ts * 1000);
        $this->assertSame('2025-08-08 00:00:00', Helper::castToCarbon($ms)?->setTimezone('UTC')->format('Y-m-d H:i:s'));
    }


    #[Test]
    public function castToCarbonCallable(): void
    {
        $this->assertSame('2025-08-08', Helper::castToCarbon(static fn () => '2025-08-08')?->format('Y-m-d'));
    }


    #[Test]
    public function castToCarbonInvalid(): void
    {
        $this->assertNull(Helper::castToCarbon(''));
        $this->assertNull(Helper::castToCarbon(null));
        $this->assertNull(Helper::castToCarbon('2025/8/8')); // невалидно: без ведущих нулей
        $this->assertNull(Helper::castToCarbon('2025_08_08'));
        $this->assertNull(Helper::castToCarbon('not-a-date'));
    }
}
