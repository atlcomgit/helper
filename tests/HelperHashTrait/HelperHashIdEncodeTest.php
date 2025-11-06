<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperHashTrait;

use Atlcom\Helper;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест методов трейта хеширования идентификаторов
 *
 * @see \Atlcom\Traits\HelperHashTrait
 */
class HelperHashIdEncodeTest extends TestCase
{
    /**
     * Тест хеширования идентификатора без пароля
     *
     * @see \Atlcom\Helper\Helper::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithoutPassword(): void
    {
        $token = Helper::hashIdEncode(12345);

        $this->assertSame('FL6H', $token);
        $this->assertSame($token, Helper::hashIdEncode('12345'));
        $this->assertSame(12345, Helper::hashIdDecode($token));
    }


    /**
     * Проверяет кодирование с паролем
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithPassword(): void
    {
        $token = Helper::hashIdEncode(12345, 'secret');

        $this->assertSame('PB3U', $token);
        $this->assertSame($token, Helper::hashIdEncode(12345, 'secret'));
        $this->assertNotSame($token, Helper::hashIdEncode(12345, 'another'));
        $this->assertSame(4, strlen($token));
        $this->assertSame(12345, Helper::hashIdDecode($token, 'secret'));
    }


    /**
     * Проверяет установленную минимальную длину токена
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithMinLength(): void
    {
        $token = Helper::hashIdEncode(12345, null, 6);

        $this->assertSame(6, strlen($token));
        $this->assertSame($token, Helper::hashIdEncode(12345, null, 6));
        $this->assertSame(12345, Helper::hashIdDecode($token));
    }


    /**
     * Проверяет использование пользовательского алфавита
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithCustomAlphabet(): void
    {
        $alphabet = 'abcdef1234';
        $token = Helper::hashIdEncode(12345, 'secret', 0, $alphabet);

        $this->assertSame('12f1ba', $token);
        $this->assertMatchesRegularExpression('/^[abcdef1234]+$/', $token);
        $this->assertSame(12345, Helper::hashIdDecode($token, 'secret', 0, $alphabet));
    }


    /**
     * Проверяет кодирование в шестнадцатеричном алфавите с минимальной длиной
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithHexAlphabet(): void
    {
        $alphabet = '0123456789abcdef';
        $token = Helper::hashIdEncode(123, '', 3, $alphabet);

        $this->assertSame('eb6', $token);
        $this->assertSame(123, Helper::hashIdDecode($token, '', 3, $alphabet));
    }


    /**
     * Проверяет разворачивание масок диапазонов в алфавите
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithRangeMask(): void
    {
        $tokenMasked = Helper::hashIdEncode(9876, null, 4, '0-9');
        $tokenExpanded = Helper::hashIdEncode(9876, null, 4, '0123456789');

        $this->assertSame($tokenExpanded, $tokenMasked);

        $tokenMaskedLetters = Helper::hashIdEncode(321, null, 0, 'a-f');
        $tokenExpandedLetters = Helper::hashIdEncode(321, null, 0, 'abcdef');

        $this->assertSame($tokenExpandedLetters, $tokenMaskedLetters);
    }


    /**
     * Проверяет обработку null значения
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithNullValue(): void
    {
        $this->assertSame('', Helper::hashIdEncode(null));
    }


    /**
     * Проверяет валидацию отрицательного идентификатора
     *
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


    /**
     * Проверяет валидацию пользовательского алфавита
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithInvalidAlphabet(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Helper::hashIdEncode(1, null, 0, 'a');
    }


    /**
     * Проверяет валидацию отрицательной минимальной длины
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdEncode()
     *
     * @return void
     */
    #[Test]
    public function hashIdEncodeWithNegativeMinLength(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Helper::hashIdEncode(1, null, -1);
    }
}
