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
    public static string $regexpUuid = '/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i';


    /**
     * Проверяет значение на формат электронной почты
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
     * Проверяет значение на формат регулярного выражения
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
     * Проверяет значение на формат номера телефона
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
     * Проверяет значение на формат идентификатора uuid
     * @see ./tests/HelperRegexpTrait/HelperRegexpValidateUuidTest.php
     *
     * @param string $value
     * @return bool
     */
    public static function regexpValidateUuid(string $value): bool
    {
        return (bool)preg_match(static::$regexpUuid, $value);
    }
}
