<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperHashTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тесты декодирования идентификаторов хеша
 *
 * @see \Atlcom\Traits\HelperHashTrait
 */
final class HelperHashIdDecodeTest extends TestCase
{
    /**
     * Проверяет декодирование токена с корректным паролем
     *
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
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeWithWrongPassword(): void
    {
        $token = Helper::hashIdEncode(12345, 'secret');
        $decoded = Helper::hashIdDecode($token, 'wrong');

        // С неправильным паролем токен невалиден и должен вернуться null
        $this->assertNull($decoded);
    }


    /**
     * Проверяет декодирование с установленной минимальной длиной
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeWithMinLength(): void
    {
        $token = Helper::hashIdEncode(12345, null, 6);
        $decoded = Helper::hashIdDecode($token);

        $this->assertSame(12345, $decoded);
    }


    /**
     * Проверяет декодирование с пользовательским алфавитом
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeWithCustomAlphabet(): void
    {
        $alphabet = 'abcdef1234';
        $token = Helper::hashIdEncode(12345, 'secret', 0, $alphabet);
        $decoded = Helper::hashIdDecode($token, 'secret', 0, $alphabet);

        $this->assertSame(12345, $decoded);
    }


    /**
     * Проверяет декодирование шестнадцатеричного токена с минимальной длиной
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeWithHexAlphabet(): void
    {
        $alphabet = '0123456789abcdef';
        $token = Helper::hashIdEncode(123, '', 3, $alphabet);
        $decoded = Helper::hashIdDecode($token, '', 3, $alphabet);

        $this->assertSame(123, $decoded);
    }


    /**
     * Проверяет декодирование токена, созданного маской диапазона
     *
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeWithRangeMask(): void
    {
        $tokenMasked = Helper::hashIdEncode(9876, null, 4, '0-9');
        $decodedMasked = Helper::hashIdDecode($tokenMasked, null, 4, '0-9');

        $this->assertSame(9876, $decodedMasked);

        $tokenMaskedLetters = Helper::hashIdEncode(321, null, 0, 'a-f');
        $decodedMaskedLetters = Helper::hashIdDecode($tokenMaskedLetters, null, 0, 'a-f');

        $this->assertSame(321, $decodedMaskedLetters);
    }


    /**
     * Проверяет декодирование некорректного токена
     *
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
