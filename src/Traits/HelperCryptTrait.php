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
    public static bool $cryptRandom = false;


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
        if (!$value || is_callable($value)) {
            return $result;
        }

        $string = match (true) {
            is_array($value) => json_encode($value),
            is_object($value) => match (true) {
                    method_exists($value, 'toArray') => json_encode((array)$value->toArray()),

                    default => json_encode((array)$value)
                },

            default => (string)$value,
        };

        $str = static::$cryptPrefix . $string;
        $password = static::cryptHash($password ?? '');
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
        $result = static::cryptHash($result);

        return $result;
    }


    /**
     * Дешифрует строку с паролем
     * @see ./tests/HelperCryptTrait/HelperCryptDecodeTest.php
     *
     * @param string $string
     * @return string|false
     */
    public static function cryptDecode(string $value, ?string $password = null): string|false
    {
        $result = '';
        if ($value == '') {
            return $result;
        }

        $str = $value;
        $password = static::cryptHash($password ?? '');
        $str = static::cryptUnHash($str);
        if (!$str) {
            return false;
        }

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
            : false;

        return $result;
    }


    /**
     * Задает случайный генератор
     * @see ./tests/HelperCryptTrait/HelperCryptMakeRandomTest.php
     *
     * @return float
     */
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
}
