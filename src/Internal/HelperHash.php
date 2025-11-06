<?php

declare(strict_types=1);

namespace Atlcom\Internal;

use InvalidArgumentException;

/**
 * @internal
 * Служебные методы для детерминированного кодирования идентификаторов
 */
final class HelperHash
{
    private const BASE_SYMBOLS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /** @var array<string, string> */
    private static array $alphabetCache = [];

    /** @var array<string, array<string, int>> */
    private static array $alphabetMapCache = [];

    /**
     * Формирует короткий токен из неотрицательного идентификатора
     *
     * @param int $value
     * @param string $password
     * @return string
     */
    public static function makeIdToken(int $value, string $password): string
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Идентификатор должен быть неотрицательным.');
        }

        $alphabet = static::alphabet($password);
        $base = strlen($alphabet);
        $digits = static::splitToBase($value, $base);

        if ($password === '') {
            return static::digitsToString($digits, $alphabet);
        }

        $mix = static::mixBytes($password, count($digits));
        $mixCount = count($mix);
        $token = '';

        foreach ($digits as $index => $digit) {
            $offset = ($mix[$index % $mixCount] + $index) % $base;
            $token .= $alphabet[($digit + $offset) % $base];
        }

        return $token;
    }


    /**
     * Возвращает идентификатор из токена или null при ошибке
     *
     * @param string $token
     * @param string $password
     * @return int|null
     */
    public static function readIdToken(string $token, string $password): ?int
    {
        $token = trim($token);
        if ($token === '') {
            return null;
        }

        $alphabet = static::alphabet($password);
        $base = strlen($alphabet);
        $map = static::alphabetMap($password);
        $length = strlen($token);

        if ($password === '') {
            $digits = [];
            for ($index = 0; $index < $length; $index++) {
                $char = $token[$index];
                if (!array_key_exists($char, $map)) {
                    return null;
                }
                $digits[] = $map[$char];
            }

            $number = static::composeFromBase($digits, $base);

            return static::makeIdToken($number, $password) === $token ? $number : null;
        }

        $mix = static::mixBytes($password, $length);
        $mixCount = count($mix);
        $digits = [];

        for ($index = 0; $index < $length; $index++) {
            $char = $token[$index];
            if (!array_key_exists($char, $map)) {
                return null;
            }

            $value = $map[$char];
            $offset = ($mix[$index % $mixCount] + $index) % $base;
            $digit = $value - $offset;
            $digit %= $base;
            if ($digit < 0) {
                $digit += $base;
            }

            $digits[] = $digit;
        }

        $number = static::composeFromBase($digits, $base);

        return static::makeIdToken($number, $password) === $token ? $number : null;
    }


    /**
     * Делит число на разряды указанного основания
     *
     * @param int $value
     * @param int $base
     * @return int[]
     */
    private static function splitToBase(int $value, int $base): array
    {
        if ($value === 0) {
            return [0];
        }

        $digits = [];
        $current = $value;

        while ($current > 0) {
            $digits[] = $current % $base;
            $current = intdiv($current, $base);
        }

        return array_reverse($digits);
    }


    /**
     * Собирает число из набора разрядов
     *
     * @param int[] $digits
     * @param int $base
     * @return int
     */
    private static function composeFromBase(array $digits, int $base): int
    {
        $value = 0;

        foreach ($digits as $digit) {
            $value = ($value * $base) + $digit;
        }

        return $value;
    }


    /**
     * Возвращает алфавит, перемешанный с учетом пароля
     *
     * @param string $password
     * @return string
     */
    private static function alphabet(string $password): string
    {
        if ($password === '') {
            return self::BASE_SYMBOLS;
        }

        $cacheKey = md5($password);

        if (isset(self::$alphabetCache[$cacheKey])) {
            return self::$alphabetCache[$cacheKey];
        }

        $characters = str_split(self::BASE_SYMBOLS);
        $mix = static::mixBytes($password, count($characters));
        $mixCount = count($mix);

        for ($index = count($characters) - 1; $index > 0; $index--) {
            $offset = ($mix[$index % $mixCount] + $mix[($mixCount - 1 - $index) % $mixCount] + $index) % ($index + 1);
            [$characters[$index], $characters[$offset]] = [$characters[$offset], $characters[$index]];
        }

        return self::$alphabetCache[$cacheKey] = implode('', $characters);
    }


    /**
     * Возвращает карту символов в индексы алфавита
     *
     * @param string $password
     * @return array<string, int>
     */
    private static function alphabetMap(string $password): array
    {
        if ($password === '') {
            return array_flip(str_split(self::BASE_SYMBOLS));
        }

        $cacheKey = md5($password);

        if (isset(self::$alphabetMapCache[$cacheKey])) {
            return self::$alphabetMapCache[$cacheKey];
        }

        return self::$alphabetMapCache[$cacheKey] = array_flip(str_split(static::alphabet($password)));
    }


    /**
     * Преобразует набор разрядов в строку по алфавиту
     *
     * @param int[] $digits
     * @param string $alphabet
     * @return string
     */
    private static function digitsToString(array $digits, string $alphabet): string
    {
        $result = '';

        foreach ($digits as $digit) {
            $result .= $alphabet[$digit];
        }

        return $result;
    }


    /**
     * Формирует последовательность байт для перемешивания
     *
     * @param string $password
     * @param int $length
     * @return int[]
     */
    private static function mixBytes(string $password, int $length): array
    {
        $length = max(1, $length);
        $seed = $password === '' ? 'hashid' : $password;
        $hash = hash('xxh128', $seed . '|' . $length);
        $binary = hex2bin($hash);

        if ($binary === false) {
            return array_fill(0, $length, 0);
        }

        $bytes = array_values(unpack('C*', $binary));
        $count = count($bytes);

        if ($length <= $count) {
            return array_slice($bytes, 0, $length);
        }

        $extended = [];
        for ($index = 0; $index < $length; $index++) {
            $extended[$index] = $bytes[$index % $count];
        }

        return $extended;
    }
}
