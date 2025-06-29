<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Enums\HelperRegexpEnum;

/**
 * Трейт для работы с regexp
 * @mixin \Atlcom\Helper
 */
trait HelperRegexpTrait
{
    /**
     * Проверяет значение строки на формат электронной почты
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateEmailTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateEmail(?string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Email->value, $value ?? '');
    }


    /**
     * Проверяет значение строки на формат регулярного выражения
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidatePatternTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidatePattern(?string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Pattern->value, $value ?? '');
    }


    /**
     * Проверяет значение строки на формат номера телефона
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidatePhoneTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidatePhone(?string $value): bool
    {
        return (bool)(
            preg_match(HelperRegexpEnum::Phone->value, $value ?? '')
            && static::stringLength(preg_replace('/[^0-9]/', '', $value ?? '') ?? '') >= 10
        );
    }


    /**
     * Проверяет значение строки на формат идентификатора uuid v4
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateUuid4Test.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateUuid4(?string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Uuid4->value, $value ?? '');
    }


    /**
     * Проверяет значение строки на формат идентификатора uuid v7
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateUuid7Test.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateUuid7(?string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Uuid7->value, $value ?? '');
    }


    /**
     * Проверяет значение строки на формат json
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateJsonTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateJson(?string $value): bool
    {
        return is_array(json_decode($value ?? '', true, 512, JSON_INVALID_UTF8_IGNORE));
    }


    /**
     * Проверяет значение строки на формат ascii (латинский алфавита и цифры)
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateAsciiTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateAscii(?string $value): bool
    {
        return $value !== null && !preg_match(HelperRegexpEnum::Ascii->value, $value ?? '');
    }


    /**
     * Проверяет значение строки на формат юникода
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateUnicodeTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateUnicode(?string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Unicode->value, $value ?? '');
    }


    /**
     * Проверяет значение строки на формат даты
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateDateTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateDate(?string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Date->value, $value ?? '');
    }


    /**
     * Проверяет значение строки на формат url
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateUrlTest.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateUrl(?string $value): bool
    {
        // Проверка по регулярному выражению
        if (!preg_match(HelperRegexpEnum::Url->value, $value)) {
            return false;
        }

        $parts = parse_url($value);
        $host = $parts['host'] ?? '';

        if (!$parts || !$host) {
            return false;
        }

        // Host не должен начинаться с точки и не быть только точкой
        if ($host[0] === '.' || $host === '.') {
            return false;
        }

        // Проверка на IPv4-вид
        if (preg_match('/^\d{1,3}(\.\d{1,3}){3}$/', $host)) {
            // Если невалидный IP, то false
            if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                return false;
            }
        }

        // Проверка user: если есть user, но нет host — невалидно
        if (isset($parts['user']) && empty($host)) {
            return false;
        }

        return true;
    }


    /**
     * Проверяет значение строки на формат ip адреса v4
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateIp4Test.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateIp4(?string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Ip4->value, $value ?? '');
    }


    /**
     * Проверяет значение строки на формат ip адреса v6
     * @see ../../tests/HelperRegexpTrait/HelperRegexpValidateIp6Test.php
     *
     * @param string|null $value
     * @return bool
     */
    public static function regexpValidateIp6(?string $value): bool
    {
        return (bool)preg_match(HelperRegexpEnum::Ip6->value, $value ?? '');
    }
}
