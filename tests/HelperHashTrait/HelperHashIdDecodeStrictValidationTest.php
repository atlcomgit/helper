<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperHashTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода hashIdDecode для проверки строгой валидации токенов
 */
class HelperHashIdDecodeStrictValidationTest extends TestCase
{
    /**
     * Проверяет, что токены с лишними символами не декодируются
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeRejectsInvalidTokens(): void
    {
        $password = 'test-password-for-validation';
        $minLength = 5;
        $alphabet = '0-9a-f';

        // Закодируем значение 1
        $validToken = Helper::hashIdEncode(1, $password, $minLength, $alphabet);
        $this->assertSame('c5fd6', $validToken);

        // Валидный токен декодируется правильно
        $decoded = Helper::hashIdDecode($validToken, $password, $minLength, $alphabet);
        $this->assertSame(1, $decoded);

        // Токен с лишними символами должен вернуть null
        $invalidToken1 = $validToken . '00';
        $decoded1 = Helper::hashIdDecode($invalidToken1, $password, $minLength, $alphabet);
        $this->assertNull($decoded1, 'Токен с лишними символами должен быть невалидным');

        // Слишком короткий токен должен вернуть null
        $invalidToken2 = substr($validToken, 0, 3);
        $decoded2 = Helper::hashIdDecode($invalidToken2, $password, $minLength, $alphabet);
        $this->assertNull($decoded2, 'Слишком короткий токен должен быть невалидным');

        // Токен с измененным символом должен вернуть null
        $invalidToken3 = 'c5fda'; // отличается от 'c5fd6' только последним символом
        $decoded3 = Helper::hashIdDecode($invalidToken3, $password, $minLength, $alphabet);
        $this->assertNull($decoded3, 'Токен с измененным символом должен быть невалидным');
    }


    /**
     * Проверяет, что токен с неправильным паролем не декодируется
     * @see \Atlcom\Traits\HelperHashTrait::hashIdDecode()
     *
     * @return void
     */
    #[Test]
    public function hashIdDecodeRejectsWrongPassword(): void
    {
        $correctPassword = 'secret';
        $wrongPassword = 'wrong';

        $token = Helper::hashIdEncode(12345, $correctPassword);

        // С правильным паролем декодируется
        $decoded = Helper::hashIdDecode($token, $correctPassword);
        $this->assertSame(12345, $decoded);

        // С неправильным паролем возвращается null
        $decodedWrong = Helper::hashIdDecode($token, $wrongPassword);
        $this->assertNull($decodedWrong, 'Токен с неправильным паролем должен быть невалидным');
    }
}
