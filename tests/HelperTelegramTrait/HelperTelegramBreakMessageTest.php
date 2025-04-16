<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperTelegramTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperTelegramTrait
 */
final class HelperTelegramBreakMessageTest extends TestCase
{
    #[Test]
    public function telegramBreakMessage(): void
    {
        $array = Helper::telegramBreakMessage('Иванов Иван Иванович', 1000, 4000, 10);
        $this->assertTrue($array === ['Иванов Иван Иванович']);

        $array = Helper::telegramBreakMessage('Иванов\nИван\nИванович', 10, 100, 1);
        $this->assertTrue($array === ['Иванов']);

        $array = Helper::telegramBreakMessage(null, 10, 100, 1);
        $this->assertTrue($array === []);
    }
}
