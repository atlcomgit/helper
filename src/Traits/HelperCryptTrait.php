<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с кодированием
 */
trait HelperCryptTrait
{
    public static int $cryptLength = 3;
    public static string $cryptPrefix = 'tkn:';
    public static string $cryptSuffix = ':tkn';
    public static bool $cryptRandom = false;
    public static int $cryptJsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;


    /**
     * Шифрует строку с паролем
     * @see ./tests/HelperCryptTrait/HelperCryptEncodeTest.php
     *
     * @param mixed $value
     * @param string|null $password
     * @param bool|null $random
     * @return string
     */
    public static function cryptEncode(mixed $value, ?string $password = null, ?bool $random = null): string
    {
        $result = '';

        if (is_callable($value)) {
            return $result;
        }

        $string = match (true) {
            is_null($value) => 'n|',
            is_integer($value) => 'i|' . (string)$value,
            is_float($value) => 'f|' . (string)$value,
            is_string($value) => 's|' . (string)$value,
            is_bool($value) => 'b|' . (string)$value,
            is_array($value) => 'a|' . json_encode($value, static::$cryptJsonFlags),
            is_object($value) => 'o|' . serialize($value),

            default => '?|' . (string)$value,
        };

        $string = gzdeflate($string, 9);
        $string = base64_encode($string);
        $str = static::$cryptPrefix . $string . static::$cryptSuffix;
        $password = static::cryptHash($password ?? getenv('APP_KEY') ?: '');
        mt_srand((int)static::cryptMakeRandom());
        $rnd = ($random ?? static::$cryptRandom) ? mt_rand(100, 255) : 100;
        for ($aa = 0; $aa < mb_strlen($str); $aa++) {
            $result .= (string)str_pad((string)mb_ord($str[$aa]), static::$cryptLength, '0', STR_PAD_LEFT); //sprintf("%03d", ord($str[$aa]));
        }
        $cc = mb_strlen($result);
        $bb = 0;

        for ($aa = 0; $aa < $cc; $aa++) {
            $lb = mb_ord($result[$aa]);
            $sc = $password ? mb_ord($password[static::intervalAround($aa, 0, mb_strlen($password) - 1)]) : 0;
            $result[$aa] = mb_chr(static::intervalAround($lb + $aa + $bb + $cc + $rnd + $sc, 48, 57));
            $bb = $lb;
        }

        $result = (string)$rnd . $result;
        $result = static::cryptShrink($result);
        $result = static::stringReverse($result);
        $result = static::cryptHash($result);

        return $result;
    }


    /**
     * Дешифрует строку с паролем
     * @see ./tests/HelperCryptTrait/HelperCryptDecodeTest.php
     *
     * @param string $string
     * @return mixed
     */
    public static function cryptDecode(string $value, ?string $password = null): mixed
    {
        $result = '';

        if ($value === null || $value == '') {
            return null;
        }

        $str = $value;
        $password = static::cryptHash($password ?? getenv('APP_KEY') ?: '');
        $str = static::cryptUnHash($str);

        if (!$str) {
            return null;
        }

        $str = static::stringReverse($str);
        $str = static::cryptUnShrink($str);
        $rnd = (int)mb_substr($str, 0, static::$cryptLength);
        $str = mb_substr($str, static::$cryptLength);
        $cc = mb_strlen($str);
        $bb = 0;

        for ($aa = 0; $aa < $cc; $aa++) {
            $lb = mb_ord($str[$aa]);
            $sc = $password ? mb_ord($password[static::intervalAround($aa, 0, mb_strlen($password) - 1)]) : 0;
            $str[$aa] = mb_chr(static::intervalAround($lb - $aa - $bb - $cc - $rnd - $sc, 48, 57));
            $bb = mb_ord($str[$aa]);
        }

        while ($str != '') {
            $result .= mb_chr((int)mb_substr($str, 0, static::$cryptLength));
            $str = mb_substr($str, static::$cryptLength);
        }

        $result = (mb_substr($result, 0, mb_strlen(static::$cryptPrefix)) === static::$cryptPrefix)
            ? mb_substr($result, mb_strlen(static::$cryptPrefix))
            : '';

        $result = (mb_substr($result, -mb_strlen(static::$cryptSuffix)) === static::$cryptSuffix)
            ? mb_substr($result, 0, -mb_strlen(static::$cryptSuffix))
            : '';

        $result = base64_decode($result);
        $result = gzinflate($result) ?: '';

        $type = explode('|', $result)[0];
        $result = static::stringDelete($result, 0, 2);
        $result = match ($type) {
            'n' => null,
            'i' => (int)$result,
            'f' => (float)$result,
            's' => (string)$result,
            'b' => (boolean)$result,
            'a' => json_decode($result, true),
            'o' => unserialize($result),
            '?' => $result,

            default => null,
        };

        return $result;
    }


    /**
     * Задает случайный генератор
     * @see ./tests/HelperCryptTrait/HelperCryptMakeRandomTest.php
     *
     * @return float
     */
    //?!? перенести в HelperInternal
    private static function cryptMakeRandom(): float
    {
        [$usec, $sec] = explode(' ', microtime());

        return (float)$sec + (float)$usec * 100000;
    }


    /**
     * Упаковывает строку из чисел
     * @see ./tests/HelperCryptTrait/HelperCryptShrinkTest.php
     *
     * @param mixed $value
     * @return string
     */
    //?!? перенести в HelperInternal
    private static function cryptShrink(string $value): string
    {
        $result = '';
        if ($value == '') {
            return $result;
        }

        for ($aa = 0; $aa < mb_strlen($value); $aa++) {
            $code = mb_substr($value, $aa, 2);
            if (mb_strlen($code) < 2) {
                $result .= $value[$aa];

            } else if (($code >= '65' && $code <= '90') || ($code >= '97' && $code <= '99')) {
                $result .= mb_chr((int)$code);
                $aa++;

            } else if ($code >= '00' && $code <= '22') {
                $result .= mb_chr((int)$code + 100);
                $aa++;

            } else {
                $result .= $value[$aa];
            }
        }
        return $result;
    }


    /**
     * Распаковывает строку из чисел
     * @see ./tests/HelperCryptTrait/HelperCryptUnShrinkTest.php
     *
     * @param string $value
     * @return string
     */
    //?!? перенести в HelperInternal
    private static function cryptUnShrink(string $value): string
    {
        $result = '';
        if ($value == '') {
            return $result;
        }

        for ($aa = 0; $aa < mb_strlen($value); $aa++) {
            $code = mb_ord($value[$aa]);
            if (($code >= 65 && $code <= 90) || ($code >= 97 && $code <= 99)) {
                $result .= $code;

            } else if ($code >= 100 && $code <= 122) {
                $result .= str_pad((string)($code - 100), 2, "0", STR_PAD_LEFT);

            } else {
                $result .= $value[$aa];
            }
        }
        return $result;
    }


    /**
     * Добавляет хеш в строку
     * @see ./tests/HelperCryptTrait/HelperCryptHashTest.php
     * 
     * @param string $value
     * @return string
     */
    //?!? перенести в HelperInternal
    private static function cryptHash(string $value): string
    {
        $chunkLength = (int)floor(mb_strlen($value) / 32);
        if ($chunkLength === 0) {
            return $value;
        }

        $hash = static::hashXxh128($value);
        $chunks = array_chunk(str_split($value), $chunkLength, false);
        $chunkIndex = 0;

        foreach ($chunks as &$chunk) {
            $chunk[] = $hash[$chunkIndex++] ?? '';
            if ($chunkIndex >= 32) {
                break;
            }
        }

        $chunks = array_map(static fn ($chunk) => implode('', $chunk), $chunks);

        return implode('', $chunks);
    }


    /**
     * Удаляет хеш из строки
     * @see ./tests/HelperCryptTrait/HelperCryptUnHashTest.php
     * 
     * @param string $str
     * @return string
     */
    //?!? перенести в HelperInternal
    private static function cryptUnHash(string $str): string
    {
        $chunkLength = (int)floor(mb_strlen($str) / 32);
        if ($chunkLength === 0) {
            return $str;
        }

        $hash = '';
        $chunks = array_chunk(str_split($str), $chunkLength, false);
        $chunkIndex = 0;

        foreach ($chunks as &$chunk) {
            $hash .= array_pop($chunk);
            $chunkIndex++;
            if ($chunkIndex >= 32) {
                break;
            }
        }

        $chunks = array_map(static fn ($chunk) => implode('', $chunk), $chunks);
        $result = implode('', $chunks);

        return static::hashXxh128($result) === $hash ? $result : '';
    }


    /**
     * Шифрует все значения элементов массива с добавлением хеша значения 'hash:crypt'
     * @see ./tests/HelperCryptTrait/HelperCryptArrayEncodeTest.php
     *
     * @param array|object $value
     * @param string|null $password
     * @param bool|null $random
     * @return array
     */
    public static function cryptArrayEncode(array|object $value, ?string $password = null, ?bool $random = null): array
    {
        $value = static::transformToArray($value);

        foreach ($value as &$v) {
            $v = (is_array($v) || is_object($v))
                ? static::cryptArrayEncode(static::transformToArray($v), $password, $random)
                : static::hashXxh128((string)$v) . ':' . static::cryptEncode($v, $password, $random);
        }

        return $value;
    }


    /**
     * Дешифрует все значения элементов массива вида 'hash:crypt'
     * @see ./tests/HelperCryptTrait/HelperCryptArrayDecodeTest.php
     *
     * @param array $value
     * @param string|null $password
     * @return array
     */
    public static function cryptArrayDecode(array $value, ?string $password = null): array
    {
        $result = [];

        foreach ($value as $key => $v) {
            if (is_array($v) || is_object($v)) {
                $v = static::cryptArrayDecode($v, $password);
            } else if (is_string($v) && static::stringSearchAny($v, ':')) {
                $vParts = static::stringSplit($v, ':');
                $vHash = $vParts[0] ?? null;
                $vDecrypted = static::cryptDecode($vParts[1] ?? null, $password);
                !(static::hashXxh128((string)$vDecrypted) === $vHash) ?: $v = $vDecrypted;
            }

            $result[$key] = $v;

            unset($value[$key]);
        }

        return $result;
    }
}
