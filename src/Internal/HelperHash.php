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
    private const MIN_ALPHABET_LENGTH = 3;
    private const MIN_LENGTH_LIMIT = 255;

    /** @var array<string, string> */
    private static array $alphabetCache = [];

    /**
     * Кодирует неотрицательное число в короткий токен по алгоритму Sqids
     *
     * @param int $value
     * @param string $password
     * @param int $minLength
     * @param string|null $alphabetOverride
     * @return string
     */
    public static function makeIdToken(
        int $value,
        string $password,
        int $minLength = 0,
        ?string $alphabetOverride = null,
    ): string {
        if ($value < 0) {
            throw new InvalidArgumentException('Идентификатор должен быть неотрицательным.');
        }

        if ($minLength < 0 || $minLength > self::MIN_LENGTH_LIMIT) {
            throw new InvalidArgumentException(
                'Минимальная длина должна быть в диапазоне от 0 до ' . self::MIN_LENGTH_LIMIT . '.'
            );
        }

        $baseAlphabet = static::prepareAlphabet($alphabetOverride);
        $alphabet = static::buildAlphabet($password, $baseAlphabet);

        return static::encodeNumbers([$value], $alphabet, $minLength);
    }


    /**
     * Декодирует токен в исходное число
     *
     * @param string $token
     * @param string $password
     * @param int $minLength
     * @param string|null $alphabetOverride
     * @return int|null
     */
    public static function readIdToken(
        string $token,
        string $password,
        int $minLength = 0,
        ?string $alphabetOverride = null,
    ): ?int {
        $token = trim($token);
        if ($token === '') {
            return null;
        }

        if ($minLength < 0 || $minLength > self::MIN_LENGTH_LIMIT) {
            throw new InvalidArgumentException(
                'Минимальная длина должна быть в диапазоне от 0 до ' . self::MIN_LENGTH_LIMIT . '.'
            );
        }

        $baseAlphabet = static::prepareAlphabet($alphabetOverride);
        $alphabet = static::buildAlphabet($password, $baseAlphabet);

        $numbers = static::decodeNumbers($token, $alphabet);

        if (count($numbers) !== 1) {
            return null;
        }

        $decodedValue = $numbers[0];

        // Проверка валидности токена через обратное кодирование.
        // Это необходимо для защиты от подделок и искаженных токенов:
        // - токен '289b6' валиден для значения 1
        // - токен '289b600' содержит лишние символы и должен быть отклонен
        // - токен '289' слишком короткий и должен быть отклонен
        // - токен с неправильным паролем не пройдет проверку
        $tokenLength = strlen($token);
        $effectiveMinLength = max($minLength, $tokenLength);

        // Кодируем декодированное значение обратно с той же длиной
        $expectedToken = static::encodeNumbers([$decodedValue], $alphabet, $effectiveMinLength);

        // Токен валиден только если он полностью совпадает с ожидаемым
        if ($expectedToken !== $token) {
            return null;
        }

        return $decodedValue;
    }


    /**
     * Готовит алфавит к работе и проверяет его корректность
     *
     * @param string|null $alphabetOverride
     * @return string
     */
    private static function prepareAlphabet(?string $alphabetOverride): string
    {
        $alphabet = $alphabetOverride === null ? self::BASE_SYMBOLS : trim($alphabetOverride);

        if ($alphabet !== self::BASE_SYMBOLS) {
            $alphabet = static::expandAlphabetMasks($alphabet);
        }

        if ($alphabet === '') {
            throw new InvalidArgumentException('Алфавит не может быть пустым.');
        }

        $characters = str_split($alphabet);
        $unique = [];

        foreach ($characters as $character) {
            if (!in_array($character, $unique, true)) {
                $unique[] = $character;
            }
        }

        if (count($unique) < self::MIN_ALPHABET_LENGTH) {
            throw new InvalidArgumentException(
                'Алфавит должен содержать минимум ' . self::MIN_ALPHABET_LENGTH . ' уникальных символа.'
            );
        }

        return implode('', $unique);
    }


    /**
     * Разворачивает маски диапазонов вида a-z или 0-9 в явные символы
     *
     * @param string $alphabet
     * @return string
     */
    private static function expandAlphabetMasks(string $alphabet): string
    {
        $result = '';
        $length = strlen($alphabet);

        for ($index = 0; $index < $length; $index++) {
            $character = $alphabet[$index];
            $rangeStartClass = static::rangeClass($character);

            if (
                $index + 2 < $length
                && $alphabet[$index + 1] === '-'
                && $rangeStartClass !== null
                && static::isRangeBoundary($alphabet[$index + 2])
                && $rangeStartClass === static::rangeClass($alphabet[$index + 2])
                && ord($character) <= ord($alphabet[$index + 2])
            ) {
                // Разворачиваем последовательность вида a-z или 0-9 в явный набор символов
                $result .= static::buildRangeString($character, $alphabet[$index + 2]);
                $index += 2;

                continue;
            }

            $result .= $character;
        }

        return $result;
    }


    /**
     * Проверяет, подходит ли символ для определения диапазона
     *
     * @param string $character
     * @return bool
     */
    private static function isRangeBoundary(string $character): bool
    {
        return $character !== ''
            && (ctype_digit($character) || ctype_lower($character) || ctype_upper($character));
    }


    /**
     * Возвращает тип диапазона для сравнения границ
     *
     * @param string $character
     * @return string|null
     */
    private static function rangeClass(string $character): ?string
    {
        if (ctype_digit($character)) {
            return 'digit';
        }

        if (ctype_lower($character)) {
            return 'lower';
        }

        if (ctype_upper($character)) {
            return 'upper';
        }

        return null;
    }


    /**
     * Строит строку диапазона между двумя символами включительно
     *
     * @param string $start
     * @param string $end
     * @return string
     */
    private static function buildRangeString(string $start, string $end): string
    {
        $range = '';

        for ($code = ord($start); $code <= ord($end); $code++) {
            $range .= chr($code);
        }

        return $range;
    }


    /**
     * Формирует рабочий алфавит с учетом пароля и внутреннего перемешивания
     *
     * @param string $password
     * @param string $baseAlphabet
     * @return string
     */
    private static function buildAlphabet(string $password, string $baseAlphabet): string
    {
        $mixedAlphabet = $password === ''
            ? $baseAlphabet
            : static::alphabet($password, $baseAlphabet);

        return static::shuffleAlphabet($mixedAlphabet);
    }


    /**
     * Реализация алгоритма Sqids для массива чисел
     *
     * @param int[] $numbers
     * @param string $alphabet
     * @param int $minLength
     * @return string
     */
    private static function encodeNumbers(array $numbers, string $alphabet, int $minLength): string
    {
        if ($numbers === []) {
            return '';
        }

        $alphabetLength = strlen($alphabet);
        $offset = count($numbers);

        foreach ($numbers as $index => $value) {
            $offset += ord($alphabet[$value % $alphabetLength]) + $index;
        }

        $offset %= $alphabetLength;
        $rotatedAlphabet = substr($alphabet, $offset) . substr($alphabet, 0, $offset);
        $prefix = $rotatedAlphabet[0];
        $workingAlphabet = strrev($rotatedAlphabet);
        $id = $prefix;

        $numbersCount = count($numbers);
        foreach ($numbers as $index => $value) {
            $digitsAlphabet = substr($workingAlphabet, 1);
            $digitsBase = strlen($digitsAlphabet);

            $digits = static::splitToBase($value, $digitsBase);
            $id .= static::digitsToString($digits, $digitsAlphabet);

            if ($index < $numbersCount - 1) {
                $id .= $workingAlphabet[0];
                $workingAlphabet = static::shuffleAlphabet($workingAlphabet);
            }
        }

        if ($minLength > strlen($id)) {
            $id .= $workingAlphabet[0];

            while ($minLength - strlen($id) > 0) {
                $workingAlphabet = static::shuffleAlphabet($workingAlphabet);
                $padLength = min($minLength - strlen($id), $alphabetLength);
                $id .= substr($workingAlphabet, 0, $padLength);
            }
        }

        return $id;
    }


    /**
     * Раскодирует токен в набор чисел согласно алгоритму Sqids
     *
     * @param string $token
     * @param string $alphabet
     * @return int[]
     */
    private static function decodeNumbers(string $token, string $alphabet): array
    {
        if ($token === '') {
            return [];
        }

        if (!static::tokenInAlphabet($token, $alphabet)) {
            return [];
        }

        $prefix = $token[0];
        $offset = strpos($alphabet, $prefix);
        if ($offset === false) {
            return [];
        }

        $rotatedAlphabet = substr($alphabet, $offset) . substr($alphabet, 0, $offset);
        $workingAlphabet = strrev($rotatedAlphabet);
        $id = substr($token, 1);

        $result = [];
        while ($id !== '') {
            $separator = $workingAlphabet[0];
            $chunks = explode($separator, $id, 2);

            if ($chunks[0] === '') {
                return $result;
            }

            $digitsAlphabet = substr($workingAlphabet, 1);
            $number = static::toNumber($chunks[0], $digitsAlphabet);
            if ($number === null) {
                return [];
            }

            $result[] = $number;

            if (array_key_exists(1, $chunks)) {
                $workingAlphabet = static::shuffleAlphabet($workingAlphabet);
            }

            $id = $chunks[1] ?? '';
        }

        return $result;
    }


    /**
     * Проверяет, что токен состоит только из символов текущего алфавита
     *
     * @param string $token
     * @param string $alphabet
     * @return bool
     */
    private static function tokenInAlphabet(string $token, string $alphabet): bool
    {
        $length = strlen($token);
        for ($index = 0; $index < $length; $index++) {
            if (strpos($alphabet, $token[$index]) === false) {
                return false;
            }
        }

        return true;
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

        if ($base < 2) {
            throw new InvalidArgumentException('Размер алфавита должен быть не меньше двух символов.');
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
     * Перемешивает алфавит согласно алгоритму Sqids
     *
     * @param string $alphabet
     * @return string
     */
    private static function shuffleAlphabet(string $alphabet): string
    {
        for ($i = 0, $j = strlen($alphabet) - 1; $j > 0; $i++, $j--) {
            $r = ($i * $j + ord($alphabet[$i]) + ord($alphabet[$j])) % strlen($alphabet);
            [$alphabet[$i], $alphabet[$r]] = [$alphabet[$r], $alphabet[$i]];
        }

        return $alphabet;
    }


    /**
     * Преобразует строку из алфавита в число
     *
     * @param string $chunk
     * @param string $alphabet
     * @return int|null
     */
    private static function toNumber(string $chunk, string $alphabet): ?int
    {
        $base = strlen($alphabet);
        if ($base < 2) {
            return null;
        }

        $digits = [];
        $length = strlen($chunk);

        for ($index = 0; $index < $length; $index++) {
            $position = strpos($alphabet, $chunk[$index]);
            if ($position === false) {
                return null;
            }

            $digits[] = $position;
        }

        return static::composeFromBase($digits, $base);
    }


    /**
     * Возвращает алфавит, перемешанный с учетом пароля
     *
     * @param string $password
     * @param string $baseAlphabet
     * @return string
     */
    private static function alphabet(string $password, string $baseAlphabet): string
    {
        if ($password === '') {
            return $baseAlphabet;
        }

        $cacheKey = md5($password . '|' . $baseAlphabet);
        if (isset(self::$alphabetCache[$cacheKey])) {
            return self::$alphabetCache[$cacheKey];
        }

        $characters = str_split($baseAlphabet);
        $mix = static::mixBytes($password, count($characters), $baseAlphabet);
        $mixCount = count($mix);

        for ($index = count($characters) - 1; $index > 0; $index--) {
            $offset = (
                $mix[$index % $mixCount]
                + $mix[($mixCount - 1 - $index) % $mixCount]
                + $index
            ) % ($index + 1);

            [$characters[$index], $characters[$offset]] = [$characters[$offset], $characters[$index]];
        }

        return self::$alphabetCache[$cacheKey] = implode('', $characters);
    }


    /**
     * Генерирует псевдослучайную последовательность байт для перемешивания алфавита
     *
     * @param string $password
     * @param int $length
     * @param string $alphabetBase
     * @return int[]
     */
    private static function mixBytes(string $password, int $length, string $alphabetBase): array
    {
        $length = max(1, $length);
        $seed = $password === '' ? 'hashid' : $password;
        $seedString = $seed . '|' . $length;

        if ($alphabetBase !== self::BASE_SYMBOLS) {
            $seedString .= '|' . $alphabetBase;
        }

        $hash = hash('xxh128', $seedString);
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
