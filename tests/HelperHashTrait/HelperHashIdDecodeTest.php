<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperHashTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест методов трейта
 * @see \Atlcom\Traits\HelperHashTrait
 */
final class HelperHashIdDecodeTest extends TestCase
{
    /**
     * Проверяет декодирование токена с корректным паролем
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeWithPassword(): void
    {
        $token = Helper::hashIdEncode(12345, 'secret');
        $decoded = Helper::hashIdDecode($token, 'secret');

        $this->assertSame(12345, $decoded);
    }


    /**
     * Проверяет влияние неверного пароля
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeWithWrongPassword(): void
    {
        $token = Helper::hashIdEncode(12345, 'secret');
        $decoded = Helper::hashIdDecode($token, 'wrong');

        $this->assertNotSame(12345, $decoded);
        $this->assertNotNull($decoded);
    }


    /**
     * Проверяет декодирование некорректного токена
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeWithInvalidToken(): void
    {
        $this->assertNull(Helper::hashIdDecode('$$$'));
        $this->assertNull(Helper::hashIdDecode(null));
    }
}
