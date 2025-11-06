<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperHashTrait;

use Atlcom\Helper;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест методов трейта
 * @see \Atlcom\Traits\HelperHashTrait
 */
final class HelperHashIdEncodeTest extends TestCase
{
    /**
     * Проверяет кодирование без пароля
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithoutPassword(): void
    {
        $token = Helper::hashIdEncode(12345);

        $this->assertSame('3d7', $token);
        $this->assertSame($token, Helper::hashIdEncode('12345'));
    }


    /**
     * Проверяет кодирование с паролем
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithPassword(): void
    {
        $token = Helper::hashIdEncode(12345, 'secret');

        $this->assertSame('I0a', $token);
        $this->assertSame($token, Helper::hashIdEncode(12345, 'secret'));
        $this->assertNotSame($token, Helper::hashIdEncode(12345, 'another'));
        $this->assertSame(3, strlen($token));
    }


    /**
     * Проверяет валидацию отрицательного идентификатора
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithNegativeId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Helper::hashIdEncode(-1);
    }
}
