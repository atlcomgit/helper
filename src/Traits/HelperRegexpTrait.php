<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с regexp
 */
trait HelperRegexpTrait
{
    public static string $regexpEmail = '/^([A-Za-z0-9_\.-]+)@([\dA-Za-z\.-]+)\.([A-Za-z\.]{2,6})$/';
    public static string $regexpPattern = '/^\/.+\/[a-z]*$/i';
    public static string $regexpPhone = '/^[\+0-9\-\(\)\s]*$/';
    public static string $regexpUuid = '/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/iD';
    public static string $regexpAscii = '/[^\x09\x10\x13\x0A\x0D\x20-\x7E]/';
    public static string $regexpUnicode = '/[^\w$\x{0080}-\x{FFFF}]+/u';


    /**
     * Проверяет значение строки на формат электронной почты
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateEmailTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateEmail(string $value): bool
    {
        return (bool)preg_match(static::$regexpEmail, $value);
    }


    /**
     * Проверяет значение строки на формат регулярного выражения
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidatePatternTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidatePattern(string $value): bool
    {
        return (bool)preg_match(static::$regexpPattern, $value);
    }


    /**
     * Проверяет значение строки на формат номера телефона
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidatePhoneTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidatePhone(string $value): bool
    {
        return (bool)(
            preg_match(static::$regexpPhone, $value)
            && static::stringLength(preg_replace('/[^0-9]/', '', $value)) >= 10
        );
    }


    /**
     * Проверяет значение строки на формат идентификатора uuid
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateUuidTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateUuid(string $value): bool
    {
        return (bool)preg_match(static::$regexpUuid, $value);
    }


    /**
     * Проверяет значение строки на формат json
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateJsonTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateJson(string $value): bool
    {
        return (bool)($array = json_decode($value, true, 512, JSON_INVALID_UTF8_IGNORE)) && is_array($array);
    }


    /**
     * Проверяет значение строки на формат ascii (латинский алфавита и цифры)
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateAsciiTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateAscii(string $value): bool
    {
        return !preg_match(static::$regexpAscii, $value);
    }


    /**
     * Проверяет значение строки на формат юникода
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateUnicodeTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateUnicode(string $value): bool
    {
        return (bool)preg_match(static::$regexpUnicode, $value);
    }
}
