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
     * Шифрует строку
     *
     * @param int|float|string|null $value
     * @param bool $shrink
     * @return string
     */
    public static function cryptEncode(
        int|float|string|null $value,
        ?string $password = null,
        ?bool $random = null,
    ): string {
        $result = '';
        if (!$value) {
            return $result;
        }
        $string = (string)$value;

        $str = static::$cryptPrefix . $string;
        $password = static::hash($password ?? '');
        mt_srand((int)static::cryptMakeRandom());
        $rnd = ($random ?? static::$cryptRandom) ? mt_rand(100, 255) : 100;
        for ($aa = 0; $aa < strlen($str); $aa++) {
            $result .= (string)str_pad((string)ord($str[$aa]), static::$cryptLength, '0', STR_PAD_LEFT); //sprintf("%03d", ord($str[$aa]));
        }
        $cc = strlen($result);
        $bb = 0;

        for ($aa = 0; $aa < $cc; $aa++) {
            $lb = ord($result[$aa]);
            $sc = $password ? ord($password[static::intervalAround(0, strlen($password) - 1, $aa)]) : 0;
            $result[$aa] = chr(static::intervalAround(48, 57, $lb + $aa + $bb + $cc + $rnd + $sc));
            $bb = $lb;
        }

        $result = (string)$rnd . $result;
        $result = static::cryptShrink($result);
        $result = static::cryptHash($result);

        return $result;
    }


    /**
     * Дешифрует строку
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
        $password = static::hash($password ?? '');
        $str = static::cryptUnHash($str);
        if (!$str) {
            return false;
        }

        $str = static::cryptUnShrink($str);
        $rnd = (int)substr($str, 0, static::$cryptLength);
        $str = substr($str, static::$cryptLength);
        $cc = strlen($str);
        $bb = 0;

        for ($aa = 0; $aa < $cc; $aa++) {
            $lb = ord($str[$aa]);
            $sc = $password ? ord($password[static::intervalAround(0, strlen($password) - 1, $aa)]) : 0;
            $str[$aa] = chr(static::intervalAround(48, 57, $lb - $aa - $bb - $cc - $rnd - $sc));
            $bb = ord($str[$aa]);
        }

        while ($str != '') {
            $result .= chr((int)substr($str, 0, static::$cryptLength));
            $str = substr($str, static::$cryptLength);
        }

        $result = (substr($result, 0, strlen(static::$cryptPrefix)) === static::$cryptPrefix)
            ? substr($result, strlen(static::$cryptPrefix))
            : false;

        return $result;
    }


    /**
     * Задает случайный генератор
     *
     * @return float
     */
    public static function cryptMakeRandom(): float
    {
        [$usec, $sec] = explode(' ', microtime());

        return (float)$sec + (float)$usec * 100000;
    }


    /**
     * Упаковывает строку из чисел
     *
     * @param mixed $value
     * @return string
     */
    public static function cryptShrink(string $value): string
    {
        $result = '';
        if ($value == '') {
            return $result;
        }

        for ($aa = 0; $aa < strlen($value); $aa++) {
            $code = substr($value, $aa, 2);
            if (strlen($code) < 2) {
                $result .= $value[$aa];
            } else if (($code >= '65' && $code <= '90') || ($code >= '97' && $code <= '99')) {
                $result .= chr((int)$code);
                $aa++;
            } else if ($code >= '00' && $code <= '22') {
                $result .= chr((int)$code + 100);
                $aa++;
            } else {
                $result .= $value[$aa];
            }
        }
        return $result;
    }


    /**
     * Распаковывает строку из чисел
     *
     * @param string $value
     * @return string
     */
    public static function cryptUnShrink(string $value): string
    {
        $result = '';
        if ($value == '') {
            return $result;
        }

        for ($aa = 0; $aa < strlen($value); $aa++) {
            $code = ord($value[$aa]);
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
     * 
     * @param string $value
     * @return string
     */
    public static function cryptHash(string $value): string
    {
        $chunkLength = (int)floor(strlen($value) / 32);
        if ($chunkLength === 0) {
            return $value;
        }

        $hash = static::hash($value);
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
     * 
     * @param string $str
     * @return string
     */
    public static function cryptUnHash(string $str): string
    {
        $chunkLength = (int)floor(strlen($str) / 32);
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

        return static::hash($result) === $hash ? $result : '';
    }
}
